<?php


namespace matfish\Blogify\services;


use Craft;
use craft\fieldlayoutelements\CustomField;
use craft\fieldlayoutelements\entries\EntryTitleField;
use matfish\Blogify\Handles;

class EntryTypeService
{
    public function addFields($fields)
    {
        $titleElement =  [
            'type' => EntryTitleField::class,
            'required' => true,
        ];
    
        $elements = array_merge([$titleElement], array_map(static function ($field) {
            return [
                'type' => CustomField::class,
                'fieldUid' => $field->uid,
                'required' => false
            ];
        }, $fields));


        $sectionService = Craft::$app->getEntries();
        $section = $sectionService->getSectionByHandle(Handles::CHANNEL);
        $entryType = $section->getEntryTypes()[0];
        $layout = $entryType->getFieldLayout();

        $layout->setTabs([
            [
                'name' => 'Primary',
                'elements' => $elements
            ]
        ]);
        $entryType->setFieldLayout($layout);

        return $sectionService->saveEntryType($entryType);
    }
}