<?php


namespace matfish\Blogify\services;


use Craft;
use craft\fieldlayoutelements\CustomField;
use matfish\Blogify\Handles;

class EntryTypeService
{
    public function addFields($fields)
    {
        $elements = array_map(static function ($field) {
            return [
                'type' => CustomField::class,
                'fieldUid' => $field->uid,
                'required' => false
            ];
        }, $fields);


        $section = Craft::$app->sections->getSectionByHandle(Handles::CHANNEL);
        $entryType = $section->getEntryTypes()[0];
        $layout = $entryType->getFieldLayout();

        $tabs = $layout->getTabs();

        $existingElements = [];

        foreach ($tabs[0]->getElements() as $el) {
            if ($el instanceof CustomField) {
                $existingElements[] = [
                    'type' => CustomField::class,
                    'fieldUid' => $el->getField()->uid,
                    'required' => false
                ];
            }
        }

        $elements = array_merge($existingElements, $elements);

        $tabs[0]->setElements($elements);

        $layout->setTabs($tabs);

        $entryType->setFieldLayout($layout);

        return Craft::$app->sections->saveEntryType($entryType);
    }
}