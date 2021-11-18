<?php


namespace matfish\Blogify\migrations\Migrators;

use Craft;
use craft\models\FieldGroup;

class BlogFieldGroupMigrator extends Migrator
{
    public static function add(): bool
    {
       $group = new FieldGroup([
            'name' => 'Blog Fields'
        ]);

       return Craft::$app->fields->saveGroup($group);

    }

    public static function remove(): bool
    {
        // removed by BlogChannelMigrator
        return true;
    }
}