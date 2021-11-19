<?php


namespace matfish\Blogify\migrations\Migrators;


use Craft;
use craft\models\AssetTransform;
use matfish\Blogify\Handles;

class BlogThumbnailTransform extends Migrator
{

    public static function add(): bool
    {
        $transform = new AssetTransform();
        $transform->name = 'Blog Thumbnail';
        $transform->handle = Handles::THUMBNAIL_TRANSFORM;
        $transform->width = 370;
        $transform->height = 254;
        $transform->mode = 'fit';
        $transform->position = 'center-center';
        $transform->quality = 0;
        $transform->interlace = 'none';
//        $transform->format = $this->request->getBodyParam('format');

        $success = Craft::$app->getAssetTransforms()->saveTransform($transform);

        if (!$success) {
            blogify_log("Failed to created thumbnail transform");
        }

        return $success;
    }

    public static function remove(): bool
    {
        $transform = Craft::$app->getAssetTransforms()->getTransformByHandle(Handles::THUMBNAIL_TRANSFORM);

        if ($transform) {
            $success = Craft::$app->getAssetTransforms()->deleteTransformById($transform->id);
        } else {
            return false;
        }

        if (!$success) {
            blogify_log("Failed to delete thumbnail transform");
        }

        return $success;
    }
}