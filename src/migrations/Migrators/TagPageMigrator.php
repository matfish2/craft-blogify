<?php


namespace matfish\Blogify\migrations\Migrators;

use craft\models\EntryType;
use craft\models\Section;
use matfish\Blogify\Handles;
use matfish\Blogify\services\SectionService;

class TagPageMigrator extends Migrator
{

    public static function add(): bool
    {
        $entryType = new EntryType([
            'name' => 'Tag Page',
            'handle' => 'tagPage',
            'hasTitleField' => true,
        ]);

        return (new SectionService())->add('Blog Tag Page',
            Handles::TAG_PAGE,
            Section::TYPE_SINGLE,
            '/tag',
            'filters/tag/_entry',
            $entryType
        );
    }

    public static function remove(): bool
    {
        return (new SectionService())->remove(Handles::TAG_PAGE);
    }
}