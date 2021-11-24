<?php


namespace matfish\Blogify\services;


use Craft;
use craft\elements\Entry;
use matfish\Blogify\Handles;

class PostViewsService
{
    public function enabled(): bool
    {
        return blogify_cache(Handles::POST_VIEWS, function () {
            return !!\Craft::$app->getFields()->getFieldByHandle(Handles::POST_VIEWS);
        });
    }

    public function verifyEnabled()
    {
        if (!(new PostViewsService())->enabled()) {
            throw new \Exception('Post views are not enabled. Did you run "php craft blogify/views/record"?');
        }
    }

    public function increment(Entry $entry)
    {
        if (!$this->enabled()) {
            return false;
        }

        // Dont increment drafts or preview
        if (!$entry->enabled || $entry->previewing) {
            return false;
        }

        try {
            $count = $entry->getFieldValue(Handles::POST_VIEWS);
        } catch (\Exception $e) {
            // if field was manually deleted from tab
            return false;
        }

        $count++;

        $entry->setFieldValue(Handles::POST_VIEWS, $count);

        return Craft::$app->getElements()->saveElement($entry);
    }
}