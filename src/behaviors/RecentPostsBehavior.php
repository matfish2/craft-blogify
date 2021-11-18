<?php


namespace matfish\Blogify\behaviors;

use craft\elements\Entry;
use matfish\Blogify\Handles;
use yii\base\Behavior;

class RecentPostsBehavior extends Behavior
{
    public function getRecentPosts()
    {
        return Entry::find()
            ->section(Handles::CHANNEL)
            ->orderBy('postDate desc');
    }
}