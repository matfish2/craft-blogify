<?php

namespace matfish\Blogify\migrations;

use Craft;
use craft\db\Migration;
use matfish\Blogify\Handles;
use matfish\Blogify\migrations\Migrators\AuthorPageMigrator;
use matfish\Blogify\migrations\Migrators\BlogAssetsVolumeMigrator;
use matfish\Blogify\migrations\Migrators\BlogCategoriesMigrator;
use matfish\Blogify\migrations\Migrators\BlogChannelMigrator;
use matfish\Blogify\migrations\Migrators\BlogFieldGroupMigrator;
use matfish\Blogify\migrations\Migrators\BlogListingMigrator;
use matfish\Blogify\migrations\Migrators\BlogTagsMigrator;
use matfish\Blogify\migrations\Migrators\BlogThumbnailTransform;
use matfish\Blogify\migrations\Migrators\CopyTemplatesMigrator;
use matfish\Blogify\migrations\Migrators\PostFieldsMigrator;
use matfish\Blogify\migrations\Migrators\TagPageMigrator;

class Install extends Migration
{
    public function safeUp()
    {

        if (Craft::$app->projectConfig->get('plugins.blogify', true) === null) {
            Craft::$app->getPlugins()->installPlugin('redactor');

            CopyTemplatesMigrator::add();

            BlogChannelMigrator::add();
            BlogListingMigrator::add();
            BlogCategoriesMigrator::add();
            BlogTagsMigrator::add();
            BlogFieldGroupMigrator::add();
            BlogAssetsVolumeMigrator::add();
            BlogThumbnailTransform::add();
            TagPageMigrator::add();
            AuthorPageMigrator::add();
            PostFieldsMigrator::add();
        }
    }

    public function safeDown()
    {
        CopyTemplatesMigrator::remove();

        BlogChannelMigrator::remove();
        BlogListingMigrator::remove();
        BlogCategoriesMigrator::remove();
        BlogTagsMigrator::remove();
        BlogAssetsVolumeMigrator::remove();
        BlogThumbnailTransform::remove();
        TagPageMigrator::remove();
        AuthorPageMigrator::remove();
        PostFieldsMigrator::remove();

        \Craft::$app->cache->delete(Handles::CHANNEL);
        \Craft::$app->cache->delete(Handles::LISTING);
        \Craft::$app->cache->delete(Handles::CATEGORIES);
        \Craft::$app->cache->delete(Handles::TAGS);

    }
}