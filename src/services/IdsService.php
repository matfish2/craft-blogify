<?php


namespace matfish\Blogify\services;


use Craft;
use matfish\Blogify\Handles;

class IdsService
{
    public function blogChannelId()
    {
        return $this->getSectionIdByHandle(Handles::CHANNEL);
    }

    public function blogListingId()
    {
        return $this->getSectionIdByHandle(Handles::LISTING);
    }

    public function categoriesGroupId()
    {
        return blogify_cache(Handles::CATEGORIES, function () {
            return (int)Craft::$app->categories->getGroupByHandle(Handles::CATEGORIES)->id;
        });
    }

    public function tagsGroupId()
    {
        return blogify_cache(Handles::CATEGORIES, function () {
            return (int)Craft::$app->tags->getTagGroupByHandle(Handles::TAGS)->id;
        });
    }

    protected function getSectionIdByHandle($handle)
    {
        return blogify_cache($handle, function () use ($handle) {
            return (int)Craft::$app->getEntries()->getSectionByHandle($handle)->id;
        });
    }
}