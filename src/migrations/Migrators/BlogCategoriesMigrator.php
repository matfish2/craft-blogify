<?php


namespace matfish\Blogify\migrations\Migrators;


use Craft;
use craft\models\CategoryGroup;
use craft\models\CategoryGroup_SiteSettings;
use matfish\Blogify\Handles;

class BlogCategoriesMigrator extends Migrator
{
    public static function add(): bool
    {
        $siteId = Craft::$app->sites->getPrimarySite()->id;

        $siteSettings = new CategoryGroup_SiteSettings([
            'siteId' => Craft::$app->sites->getPrimarySite()->id,
            'hasUrls' => true,
            'uriFormat' => 'blog/category/{slug}',
            'template' => 'blogify/filters/category/_entry',
        ]);

        $categoryGroup = new CategoryGroup([
                'name' => 'Blogify Categories',
                'handle' => Handles::CATEGORIES,
            ]
        );

        $categoryGroup->setSiteSettings([$siteId => $siteSettings]);

        return Craft::$app->categories->saveGroup($categoryGroup);
    }

    public static function remove(): bool
    {
        echo "Deleting category group";

        $group = Craft::$app->categories->getGroupByHandle(Handles::CATEGORIES);

        if ($group) {
            try {
                $res =  Craft::$app->categories->deleteGroup($group);
                echo $res ? 'Deleted categories group' : 'Failed to delete categories group';
                return $res;
            } catch (\Exception $e) {
                echo "Failed to delete category group with message " . $e->getMessage();
            }
        }

        return false;
    }
}