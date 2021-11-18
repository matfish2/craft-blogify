<?php

namespace matfish\Blogify\behaviors;

use craft\elements\Entry;

class PostPaginationBehavior extends \yii\base\Behavior
{

    public function getPrevPost()
    {
        return $this->owner->getPrev($this->getAllPosts());
    }

    public function getNextPost()
    {
        return $this->owner->getNext($this->getAllPosts());
    }

    private function getAllPosts()
    {
        return Entry::find()->section('blogify')->orderBy('postDate asc');
    }
}