<?php


namespace matfish\Blogify\behaviors;


use Craft;
use craft\fields\Matrix;
use matfish\Blogify\Blogify;
use matfish\Blogify\Handles;
use matfish\Blogify\services\PostViewsService;
use yii\base\Behavior;

class PostFieldsBehavior extends Behavior
{
    public function getImage()
    {
        return $this->owner->blogifyPostImage->one();
    }

    public function thumbnail()
    {
        return $this->getImage()->setTransform(Handles::THUMBNAIL_TRANSFORM);
    }

    public function getExcerpt()
    {
        return $this->owner->blogifyPostExcerpt;
    }

    public function getContent()
    {
        $field = Craft::$app->fields->getFieldByHandle(Handles::POST_CONTENT);

        if ($field instanceof Matrix) {
            $html = '';
            $view = Craft::$app->getView();
            $view->setTemplateMode($view::TEMPLATE_MODE_SITE);
            foreach ($this->owner->blogifyPostContent->all() as $block) {
                $templatePath = Blogify::getInstance()->settings->matrixTemplatesPath . DIRECTORY_SEPARATOR . $block->type;
                if ($view->doesTemplateExist($templatePath)) {
                    $html .= $view->renderTemplate($templatePath, [
                        'block' => $block
                    ]);
                } else {
                    $html .= "<p style='color:red'>Template {$templatePath} not found</p>";
                }
            }

            return $html;
        } else {
            return $this->owner->blogifyPostContent;
        }
    }

    public function views()
    {
        (new PostViewsService())->verifyEnabled();

        if (isset($this->owner[Handles::POST_VIEWS])) {
            return $this->owner[Handles::POST_VIEWS];
        }

        return null;
    }
}