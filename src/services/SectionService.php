<?php

namespace matfish\Blogify\services;

use Craft;
use craft\models\Section;
use craft\models\Section_SiteSettings;

class SectionService
{

    public function add($name, $handle, $type, $url, $template): bool
    {
        blogify_log("Creating section {$name}");

        $section = Craft::$app->sections->getSectionByHandle($handle);

        if ($section) {
            blogify_log("Section {$name} exists. Skipping");
            return true;
        }

        $section = new Section([
            'name' => $name,
            'handle' => $handle,
            'type' => $type,
            'siteSettings' => [
                new Section_SiteSettings([
                    'siteId' => Craft::$app->sites->getPrimarySite()->id,
                    'enabledByDefault' => true,
                    'hasUrls' => true,
                    'uriFormat' => 'blog' . $url,
                    'template' => 'blogify/' . $template,
                ]),
            ]
        ]);

        if (!Craft::$app->sections->saveSection($section)) {
            blogify_log("Failed to create section {$name}");
            blogify_log(json_encode($section->getErrors()));
            throw new \Exception("Failed to create section {$name}");
        }

        return true;
    }

    public function remove($handle): bool
    {
        blogify_log("Removing section {$handle}");

        $section = Craft::$app->sections->getSectionByHandle($handle);

        if ($section) {
            return Craft::$app->sections->deleteSection($section);
        }

        return false;
    }
}