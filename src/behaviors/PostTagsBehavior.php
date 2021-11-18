<?php


namespace matfish\Blogify\behaviors;


use yii\base\Behavior;

class PostTagsBehavior extends Behavior
{
    public function getTags() {
        return $this->owner->blogifyPostTags;
    }
}