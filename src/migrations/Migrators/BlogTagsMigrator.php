<?php


namespace matfish\Blogify\migrations\Migrators;


use Craft;
use craft\models\TagGroup;
use matfish\Blogify\Blogify;
use matfish\Blogify\Handles;

class BlogTagsMigrator extends Migrator
{
    public static function add(): bool
    {
        $group = Craft::$app->tags->getTagGroupByHandle(Handles::TAGS);

        if ($group) {
            blogify_log("Tag group already exists. Skipping.");
            return true;
        }

        $tagsGroup = new TagGroup([
                'name' => 'Blog Tags',
                'handle' => Handles::TAGS
            ]
        );

        return Craft::$app->tags->saveTagGroup($tagsGroup);
    }

    public static function remove(): bool
    {
        $group = Craft::$app->tags->getTagGroupByHandle(Handles::TAGS);

        if ($group) {
            try {
                $res = Craft::$app->tags->deleteTagGroup($group);
                blogify_log($res ? 'Deleted tags group' : 'Failed to delete tag group');
                return $res;
            } catch (\Exception $e) {
                blogify_log('Failed to delete tag group with message ' . $e->getMessage());
            }
        }

        return false;
    }
}