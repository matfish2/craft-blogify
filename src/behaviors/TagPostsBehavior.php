<?php


namespace matfish\Blogify\behaviors;

use craft\elements\Entry;
use matfish\Blogify\Handles;
use yii\base\Behavior;

class TagPostsBehavior extends Behavior
{
    public function getPosts()
    {
        return Entry::find()->section(Handles::CHANNEL)->relatedTo($this->owner);
    }
}