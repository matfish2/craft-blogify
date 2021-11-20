<?php


namespace matfish\Blogify\migrations\Migrators;


use Craft;
use craft\volumes\Local;
use matfish\Blogify\Handles;

class BlogAssetsVolumeMigrator extends Migrator
{

    public static function add(): bool
    {
        blogify_log("Adding assets volume...");

        $volumesService = Craft::$app->getVolumes();

        $volume = $volumesService->createVolume([
            'type' => Local::class,
            'name' => 'Blog Assets',
            'handle' => Handles::ASSETS,
            'hasUrls' => 1,
            'url' => '@web/blogify',
            'settings' => [
                'path' => '@webroot/blogify'
            ]
        ]);

        $volumesService->saveVolume($volume);

        return true;
    }

    public static function remove(): bool
    {
        blogify_log("Removing assets volume...");

        $volume = Craft::$app->volumes->getVolumeByHandle(Handles::ASSETS);

        if ($volume) {
            try {
                $res = Craft::$app->getVolumes()->deleteVolumeById($volume->id);
                blogify_log($res ? 'Deleted assets volume' : 'Failed to delete assets volume');
                return $res;
            } catch (\Exception $e) {
                blogify_log('Failed to delete assets volume with message ' . $e->getMessage());
            }
        }

        return true;
    }
}