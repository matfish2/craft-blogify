<?php

namespace matfish\Blogify\services;

use Craft;
use craft\models\EntryType;
use craft\models\Section;
use craft\models\Section_SiteSettings;

class SectionService
{

    public function add($name, $handle, $type, $url, $template, EntryType $entryType): bool
    {
        blogify_log("Creating section {$name}");

        $sectionService = Craft::$app->getEntries();
        $section = $sectionService->getSectionByHandle($handle);

        if ($section) {
            blogify_log("Section {$name} exists. Skipping");
            return true;
        }

        $allSitesSettings = [];

        foreach (Craft::$app->getSites()->getAllSiteIds() as $siteId) {
            $allSitesSettings[$siteId] = new Section_SiteSettings([
                'siteId' => $siteId,
                'enabledByDefault' => true,
                'hasUrls' => true,
                'uriFormat' => 'blog' . $url,
                'template' => 'blogify/' . $template,
            ]);
        }

        $section = new Section([
            'name' => $name,
            'handle' => $handle,
            'type' => $type,
            'siteSettings' => $allSitesSettings
        ]);

        $sectionService->saveEntryType($entryType);
        $et = $sectionService->getEntryTypeByHandle('posts');

        $section->setEntryTypes([
            $et
        ]);

        if (!$sectionService->saveSection($section)) {
            blogify_log("Failed to create section {$name}");
            blogify_log(json_encode($section->getErrors()));
            throw new \Exception("Failed to create section {$name}");
        }

        return true;
    }

    public function remove($handle): bool
    {
        blogify_log("Removing section {$handle}");
        $sectionService = Craft::$app->getEntries();

        $section = $sectionService->getSectionByHandle($handle);

        if ($section) {
            return $sectionService->deleteSection($section);
        }

        return false;
    }
}