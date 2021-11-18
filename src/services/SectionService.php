<?php

namespace matfish\Blogify\services;

use Craft;
use craft\models\Section;
use craft\models\Section_SiteSettings;

class SectionService
{

    public function add($name, $handle, $type, $url, $template) : bool
    {
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

        return Craft::$app->sections->saveSection($section);
    }

    public function remove($handle) : bool
    {
         $section = Craft::$app->sections->getSectionByHandle($handle);

        if ($section) {
            return Craft::$app->sections->deleteSection($section);
        }

        return false;
    }
}