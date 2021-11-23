<?php


namespace matfish\Blogify\services;


use Craft;
use craft\records\FieldGroup;
use matfish\Blogify\Handles;

class FieldsService
{

    public function add($name, $handle, $type, $attributes = null, $settings = null)
    {
        if ($field = Craft::$app->fields->getFieldByHandle($handle)) {
            blogify_log("Field {$handle} already exists");
            return $field;
        }

        blogify_log("Creating field {$name}");

        $groupId = $this->getFieldGroupId();
        $fieldsService = Craft::$app->getFields();

        $params = [
            'type' => $type,
            'groupId' => $groupId,
            'name' => $name,
            'handle' => $handle
        ];

        if ($attributes) {
            $params = array_merge($params, $attributes);
        }

        if ($settings) {
            $params['settings'] = $settings;
        }

        $field = $fieldsService->createField($params);

        if (!$fieldsService->saveField($field)) {
            blogify_log(json_encode($field->getErrors()));
            throw new \Exception("Failed to save {$handle} field");
        }

        return $field;
    }

    public function remove($handle): bool
    {
        blogify_log("Removing field {$handle}");

        $field = Craft::$app->fields->getFieldByHandle($handle);;

        if ($field) {
            return Craft::$app->fields->deleteField($field);
        }

        return false;
    }

    private function getFieldGroupId()
    {
        $group = FieldGroup::findOne([
            'name' => Handles::BLOG_FIELDS_GROUP_NAME
        ]);

        if ($group) {
            return $group->id;
        }

        $group = FieldGroup::findOne('1=1');

        blogify_log("Failed to find blog fields group. Using {$group->name} group instead");

        return $group->id;
    }
}