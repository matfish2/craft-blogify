<?php


namespace matfish\Blogify\migrations\Migrators;


use Craft;
use matfish\Blogify\fieldtypes\ReadOnlyNumber;
use matfish\Blogify\Handles;
use matfish\Blogify\services\EntryTypeService;
use matfish\Blogify\services\FieldsService;
use matfish\Blogify\services\PostViewsService;

class PostViewsMigrator extends Migrator
{

    public static function add(): bool
    {
        Craft::$app->cache->delete(Handles::POST_VIEWS);

        $field = Craft::$app->getFields()->getFieldByHandle(Handles::POST_VIEWS);

        if ($field) {
            blogify_log("Already added post views field. Aborting");
            return false;
        }

        $field = (new FieldsService())->add('Post Views', Handles::POST_VIEWS, ReadOnlyNumber::class, null, [
            'defaultValue' => [
                'value' => 0,
                'locale' => 'en-US'
            ]
        ]);

        return (new EntryTypeService())->addFields([$field]);
    }

    public static function remove(): bool
    {
        blogify_log("Removing post views field");
        Craft::$app->cache->delete(Handles::POST_VIEWS);

        if ((new PostViewsService())->enabled()) {
            $field = Craft::$app->getFields()->getFieldByHandle(Handles::POST_VIEWS);
            if ($field) {
                return Craft::$app->getFields()->deleteField($field);
            }

            return false;
        }

        return false;
    }
}