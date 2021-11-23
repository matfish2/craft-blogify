<?php


namespace matfish\Blogify\migrations\Migrators;

use Craft;
use craft\models\FieldGroup;
use craft\records\FieldGroup as FieldGroupRecord;
use matfish\Blogify\Handles;

class BlogFieldGroupMigrator extends Migrator
{
    public static function add(): bool
    {
        blogify_log("Adding Blog Fields group");

        $group = new FieldGroup([
            'name' => Handles::BLOG_FIELDS_GROUP_NAME
        ]);

        return Craft::$app->fields->saveGroup($group);

    }

    public static function remove(): bool
    {
        $name = Handles::BLOG_FIELDS_GROUP_NAME;

        $group = FieldGroupRecord::findOne([
            'name' => $name
        ]);

        if ($group) {
            blogify_log("Removing {$name} group");
            $group->delete();
            return true;
        }

        blogify_log("Field group {$name} not found. Skipping.");
        return false;
    }
}