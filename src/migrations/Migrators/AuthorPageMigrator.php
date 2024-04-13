<?php


namespace matfish\Blogify\migrations\Migrators;


use Craft;
use craft\models\EntryType;
use craft\models\Section;
use craft\models\Section_SiteSettings;
use matfish\Blogify\Handles;
use matfish\Blogify\migrations\Migrators\Migrator;
use matfish\Blogify\services\SectionService;

class AuthorPageMigrator extends Migrator
{

    public static function add(): bool
    {
        $entryType = new EntryType([
            'name' => 'Author Page',
            'handle' => 'authorPage',
            'hasTitleField' => true,
        ]);

        return (new SectionService())->add('Blog Author Page',
            Handles::AUTHOR_PAGE,
            Section::TYPE_SINGLE,
            '/author',
            'filters/author/_entry',
            $entryType
        );
    }

    public static function remove(): bool
    {
        return (new SectionService())->remove(Handles::AUTHOR_PAGE);
    }
}