<?php


namespace matfish\Blogify\behaviors;

use craft\elements\Entry;
use matfish\Blogify\Handles;
use yii\base\Behavior;

class AuthorPostsBehavior extends Behavior
{
    public function getPosts()
    {
        return Entry::find()->section(Handles::CHANNEL)
        ->join('INNER JOIN', '{{%entries_authors}}', '[[elements_sites]].[[elementId]] = {{%entries_authors}}.[[entryId]]')
        ->where("{{%entries_authors}}.[[authorId]]={$this->owner->id}");
    }
}   