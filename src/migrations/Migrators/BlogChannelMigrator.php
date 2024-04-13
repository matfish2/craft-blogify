<?php


namespace matfish\Blogify\migrations\Migrators;


use Craft;
use craft\models\EntryType;
use craft\models\Section;
use matfish\Blogify\Handles;
use matfish\Blogify\services\SectionService;

class BlogChannelMigrator extends Migrator
{
    public static function add(): bool
    {
        \Craft::$app->cache->delete(Handles::CHANNEL);

        $entryType = new EntryType([
            'name' => 'Post',
            'handle' => 'post',
            'hasTitleField' => true,

        ]);
        return (new SectionService())->add('Blog', Handles::CHANNEL, Section::TYPE_CHANNEL, '/{slug}', 'post/_entry', $entryType);
    }

    public static function remove(): bool
    {
        \Craft::$app->cache->delete(Handles::CHANNEL);

        return (new SectionService())->remove(Handles::CHANNEL);
    }
}