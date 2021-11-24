<?php


namespace matfish\Blogify\services;


use Craft;
use matfish\Blogify\Handles;

class EntryTypeService
{
    public function addFields($fields)
    {
        $section = Craft::$app->sections->getSectionByHandle(Handles::CHANNEL);
        $entryType = $section->getEntryTypes()[0];
        $layout = $entryType->getFieldLayout();

        $tabs = $layout->getTabs();

        $tabs[0]->setFields(array_merge($tabs[0]->getFields(), $fields));

        $layout->setTabs($tabs);

        $entryType->setFieldLayout($layout);

        return Craft::$app->sections->saveEntryType($entryType);
    }
}