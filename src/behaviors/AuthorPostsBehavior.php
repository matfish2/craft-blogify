<?php


namespace matfish\Blogify\behaviors;

use craft\elements\Entry;
use matfish\Blogify\Handles;
use yii\base\Behavior;

class AuthorPostsBehavior extends Behavior
{
    public function getPosts()
    {
        return Entry::find()->section(Handles::CHANNEL)->where('authorId='.$this->owner->id);
    }
}