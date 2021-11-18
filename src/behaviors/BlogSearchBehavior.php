<?php


namespace matfish\Blogify\behaviors;


use craft\elements\Entry;
use matfish\Blogify\Handles;
use yii\base\Behavior;

class BlogSearchBehavior extends Behavior
{
    public function searchPosts($query, $limit = null)
    {
        return Entry::find()
            ->section(Handles::CHANNEL)
            ->limit($limit)
            ->search($query);
    }
}