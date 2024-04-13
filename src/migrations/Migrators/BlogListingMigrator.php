<?php


namespace matfish\Blogify\migrations\Migrators;

use Craft;
use craft\models\EntryType;
use craft\models\Section;
use craft\models\Section_SiteSettings;
use matfish\Blogify\Handles;
use matfish\Blogify\services\SectionService;

class BlogListingMigrator extends Migrator
{
    public static function add(): bool
    {
        $entryType = new EntryType([
            'name' => 'Blog Index',
            'handle' => 'blogIndex',
            'hasTitleField' => true,
        ]);

        return (new SectionService())->add('Blog Listing',
            Handles::LISTING,
            Section::TYPE_SINGLE,
            '/index',
            'listing/_entry',
            $entryType
        );
    }

    public static function remove(): bool
    {
        return (new SectionService())->remove(Handles::LISTING);
    }
}