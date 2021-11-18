<?php


namespace matfish\Blogify\behaviors;

use craft\elements\Entry;
use matfish\Blogify\Handles;
use yii\base\Behavior;

class PostCategoriesBehavior extends Behavior
{
    public function getCategories()
    {
        return $this->owner->blogifyPostCategories;
    }

    public function getRelatedPosts()
    {
        return Entry::find()
            ->section(Handles::CHANNEL)
            ->relatedTo($this->getCategories())
            ->id('not ' . $this->owner->id);
    }
}