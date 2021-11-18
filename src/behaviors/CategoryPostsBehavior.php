<?php


namespace matfish\Blogify\behaviors;


use craft\elements\Entry;
use matfish\Blogify\Handles;
use yii\base\Behavior;

class CategoryPostsBehavior extends Behavior
{
    public function getPosts($limit = null) {
        return Entry::find()
            ->section(Handles::CHANNEL)
            ->relatedTo($this->owner)
            ->limit($limit);
    }
}