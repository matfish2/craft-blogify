<?php

namespace matfish\Blogify;


use Cassandra\Set;
use Craft;
use craft\elements\Category;
use craft\elements\Entry;
use craft\elements\Tag;
use craft\elements\User;
use craft\events\DefineBehaviorsEvent;
use craft\events\TemplateEvent;
use craft\web\View;
use matfish\Blogify\behaviors\AuthorPostsBehavior;
use matfish\Blogify\behaviors\CategoryPostsBehavior;
use matfish\Blogify\behaviors\PostCategoriesBehavior;
use matfish\Blogify\behaviors\PostFieldsBehavior;
use matfish\Blogify\behaviors\PostPaginationBehavior;
use matfish\Blogify\behaviors\PostTagsBehavior;
use matfish\Blogify\behaviors\TagPostsBehavior;
use matfish\Blogify\models\Settings;
use matfish\Blogify\services\IdsService;
use matfish\Blogify\services\PostViewsService;
use matfish\Blogify\twigextensions\BlogTwigExtension;
use yii\base\Event;

class Blogify extends \craft\base\Plugin
{
    public function init()
    {
        parent::init();

        if (Craft::$app->getRequest()->getIsSiteRequest()) {
            $this->registerBehaviors();
            $this->registerTwigExtensions();

            if (
                (new PostViewsService)->enabled() &&
                !in_array(Craft::$app->request->userIP, Blogify::getInstance()->settings->postViewsExcludeIps)
            ) {
                $this->registerPostViewEventHandler();
            }
        }

        $this->registerControllers();
    }

    public function registerPostViewEventHandler()
    {
        Event::on(
            View::class,
            View::EVENT_AFTER_RENDER_PAGE_TEMPLATE,
            function (TemplateEvent $event) {
                if (isset($event->variables['entry'])) {
                    $entry = $event->variables['entry'];
                    $channelId = (new IdsService())->blogChannelId();
                    if (isset($entry->sectionId) && (int)$entry->sectionId === $channelId) {
                        (new PostViewsService())->increment($entry);
                    }
                }
            }
        );
    }

    protected function createSettingsModel() : Settings
    {
        return new Settings();
    }

    protected function registerBehaviors()
    {
        Event::on(
            Category::class,
            Category::EVENT_DEFINE_BEHAVIORS,
            function (DefineBehaviorsEvent $event) {
                if ((int)$event->sender->groupId === (new IdsService())->categoriesGroupId()) {
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

                if ((int)$event->sender->sectionId === (new IdsService())->blogChannelId()) {
                    $event->sender->attachBehaviors([
                        PostPaginationBehavior::class,
                        PostCategoriesBehavior::class,
                        PostTagsBehavior::class,
                        PostFieldsBehavior::class,
                    ]);
                }
            }
        );
    }

    private function registerControllers()
    {
        if (Craft::$app->getRequest()->getIsConsoleRequest()) {
            $this->controllerNamespace = 'matfish\\Blogify\\console\\controllers';
        }
    }

    private function registerTwigExtensions()
    {
        Craft::$app->view->registerTwigExtension(new BlogTwigExtension());
    }
}