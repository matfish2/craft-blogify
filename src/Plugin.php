<?php

namespace matfish\Blogify;


use Craft;
use craft\elements\Category;
use craft\elements\Entry;
use craft\elements\Tag;
use craft\elements\User;
use craft\events\DefineBehaviorsEvent;
use matfish\Blogify\behaviors\AllCategoriesBehavior;
use matfish\Blogify\behaviors\AllTagsBehavior;
use matfish\Blogify\behaviors\AuthorPostsBehavior;
use matfish\Blogify\behaviors\BlogSearchBehavior;
use matfish\Blogify\behaviors\CategoryPostsBehavior;
use matfish\Blogify\behaviors\PostCategoriesBehavior;
use matfish\Blogify\behaviors\PostFieldsBehavior;
use matfish\Blogify\behaviors\PostPaginationBehavior;
use matfish\Blogify\behaviors\PostTagsBehavior;
use matfish\Blogify\behaviors\RecentPostsBehavior;
use matfish\Blogify\behaviors\TagPostsBehavior;
use matfish\Blogify\twigextensions\BlogGlobalsTwigExtension;
use yii\base\Event;

class Plugin extends \craft\base\Plugin
{
    public function init()
    {
        parent::init();

        if (Craft::$app->getRequest()->getIsSiteRequest()) {
            $this->registerBehaviors();
            $this->registerTwigExtensions();
        }

        $this->registerControllers();

    }

    protected function registerBehaviors()
    {
        Event::on(
            Category::class,
            Category::EVENT_DEFINE_BEHAVIORS,
            function (DefineBehaviorsEvent $event) {
                if ((int)$event->sender->groupId === (int)$this->getCategoryGroupId()) {
                    $event->sender->attachBehaviors([
                        CategoryPostsBehavior::class
                    ]);
                }
            }
        );

        Event::on(
            Tag::class,
            Tag::EVENT_DEFINE_BEHAVIORS,
            function (DefineBehaviorsEvent $event) {
                $event->sender->attachBehaviors([
                    TagPostsBehavior::class
                ]);
            }
        );

        Event::on(
            User::class,
            User::EVENT_DEFINE_BEHAVIORS,
            function (DefineBehaviorsEvent $event) {
                $event->sender->attachBehaviors([
                    AuthorPostsBehavior::class
                ]);
            }
        );

        Event::on(
            Entry::class,
            Entry::EVENT_DEFINE_BEHAVIORS,
            function (DefineBehaviorsEvent $event) {

                // If called during installation abort
                if (is_null($event->sender->sectionId)) {
                    return;
                }

                if ($event->sender->sectionId === $this->getSectionId(Handles::CHANNEL)) {
                    $event->sender->attachBehaviors([
                        PostPaginationBehavior::class,
                        PostCategoriesBehavior::class,
                        PostTagsBehavior::class,
                        PostFieldsBehavior::class,
                        RecentPostsBehavior::class,
                    ]);
                } elseif ($event->sender->sectionId === $this->getSectionId(Handles::LISTING)) {
                    $event->sender->attachBehaviors([
                        RecentPostsBehavior::class,
                        BlogSearchBehavior::class
                    ]);
                }
            }
        );
    }

    private function getSectionId($handle)
    {
        return \Craft::$app->cache->getOrSet($handle, function () use ($handle) {
            return Craft::$app->sections->getSectionByHandle($handle)->id;
        }, 60 * 60 * 24 * 365);
    }

    private function getCategoryGroupId()
    {
        return \Craft::$app->cache->getOrSet(Handles::CATEGORIES, function () {
            return Craft::$app->categories->getGroupByHandle(Handles::CATEGORIES)->id;
        }, 60 * 60 * 24 * 365);
    }

    private function registerControllers()
    {
        if (Craft::$app->getRequest()->getIsConsoleRequest()) {
            $this->controllerNamespace = 'matfish\\Blogify\\console\\controllers';
        }
    }

    private function registerTwigExtensions()
    {
        Craft::$app->view->registerTwigExtension(new BlogGlobalsTwigExtension());
    }
}