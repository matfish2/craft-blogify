<?php


namespace matfish\Blogify\migrations\Migrators;


use Craft;
use craft\models\AssetTransform;
use matfish\Blogify\Handles;

class BlogThumbnailTransform extends Migrator
{

    public static function add(): bool
    {
        $transform = Craft::$app->getAssetTransforms()->getTransformByHandle(Handles::THUMBNAIL_TRANSFORM);

        if ($transform) {
            blogify_log("Thumbnail transform already exists. Skipping");
            return true;
        }

        blogify_log("Adding thumbnail transform...");

        $transform = new AssetTransform();
        $transform->name = 'Blog Thumbnail';
        $transform->handle = Handles::THUMBNAIL_TRANSFORM;
        $transform->width = 370;
        $transform->height = 254;
        $transform->mode = 'fit';
        $transform->position = 'center-center';
        $transform->quality = 0;
        $transform->interlace = 'none';

        $success = Craft::$app->getAssetTransforms()->saveTransform($transform);

        if (!$success) {
            blogify_log("Failed to created thumbnail transform");
        }

        return $success;
    }

    public static function remove(): bool
    {
        blogify_log("Removing thumbnail transform...");

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