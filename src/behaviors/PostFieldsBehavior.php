<?php


namespace matfish\Blogify\behaviors;


use yii\base\Behavior;

class PostFieldsBehavior extends Behavior
{
    public function getImage()
    {
        return $this->owner->blogifyPostImage->one();
    }

    public function getExcerpt()
    {
        return $this->owner->blogifyPostExcerpt;
    }
}