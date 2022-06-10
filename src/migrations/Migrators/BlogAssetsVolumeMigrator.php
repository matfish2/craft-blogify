<?php


namespace matfish\Blogify\migrations\Migrators;


use Craft;
use craft\base\Fs;
use craft\base\FsInterface;
use craft\fs\Local;
use craft\models\Volume;
use matfish\Blogify\Handles;

class BlogAssetsVolumeMigrator extends Migrator
{

    public static function add(): bool
    {
        blogify_log("Adding assets volume...");

        $volume = Craft::$app->volumes->getVolumeByHandle(Handles::ASSETS);

        if ($volume) {
            blogify_log("Volume already exists. Skipping.");
            return true;
        }

        $fsService = Craft::$app->getFs();

        /** @var FsInterface|Fs $fs */
        $fs = $fsService->createFilesystem([
            'type' => Local::class,
            'name' => 'Local',
            'handle' => 'local',
            'hasUrls' => true,
            'url' => '@web/blogify',
            'settings' => [
                'path' => '@webroot/blogify'
            ]
        ]);

        if (!$fsService->saveFilesystem($fs)) {
            die('Couldn’t save filesystem.');
        }

        Craft::$app->getVolumes()->saveVolume(new Volume(
            [
                'fs' => 'local',
                'name' => 'Blog Assets',
                'handle' => Handles::ASSETS
            ]
        ));

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