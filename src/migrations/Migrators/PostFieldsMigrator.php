<?php


namespace matfish\Blogify\migrations\Migrators;

use Craft;
use craft\fields\Assets;
use craft\fields\Categories;
use craft\fields\PlainText;
use craft\fields\Tags;
use matfish\Blogify\Handles;
use matfish\Blogify\services\EntryTypeService;
use matfish\Blogify\services\FieldsService;

class PostFieldsMigrator extends Migrator
{
    public static function add(): bool
    {
        $s = new FieldsService();
        $imagesFolderId = self::getImagesFolderId();

        $image = $s->add('Post Image',
            Handles::POST_IMAGE,
            Assets::class,
            [
                'required' => true
            ],
            [
                'limit' => 1,
                'sources' => '*',
                'defaultUploadLocationSource' => $imagesFolderId,
                'singleUploadLocationSource' => $imagesFolderId,
                'defaultUploadLocationSubpath' => '',
                'singleUploadLocationSubpath' => '',
                'allowUploads' => 1,
                'viewMode' => 'list',
                'previewMode' => 'full'
            ]
        );

        $content = $s->add('Post Content',
            Handles::POST_CONTENT,
            'craft\ckeditor\Field',
            [
                'required' => true,
                'searchable' => true
            ]
        );

        $excerpt = $s->add(
            'Post Excerpt',
            Handles::POST_EXCERPT,
            PlainText::class,
            [
                'multiline' => true,

            ]
        );

        $catGroup = Craft::$app->categories->getGroupByHandle('blogifyCategories');

        $categories = $s->add(
            'Post Categories',
            Handles::POST_CATEGORIES,
            Categories::class,
            [
                'source' => "group:" . $catGroup->uid,
                'required' => true
            ]
        );

        $tagGroup = Craft::$app->tags->getTagGroupByHandle(Handles::TAGS);

        $tags = $s->add(
            'Post Tags',
            Handles::POST_TAGS,
            Tags::class,
            [
                'source' => "taggroup:" . $tagGroup->uid,
                'required' => false
            ]
        );

        return (new EntryTypeService())->addFields([
            $image,
            $excerpt,
            $content,
            $categories,
            $tags
        ]);
    }

    public static function remove(): bool
    {
        $s = new FieldsService();
        $s->remove(Handles::POST_IMAGE);
        $s->remove(Handles::POST_CONTENT);
        $s->remove(Handles::POST_EXCERPT);
        $s->remove(Handles::POST_CATEGORIES);
        $s->remove(Handles::POST_TAGS);

        return true;
    }

    public static function getImagesFolderId()
    {
        $volume = Craft::$app->volumes->getVolumeByHandle('blogifyAssets');

        return 'volume:' . $volume->uid;
    }
}