<?php


namespace matfish\Blogify\migrations\Migrators;

use Craft;
use matfish\Blogify\Handles;

class CopyTemplatesMigrator extends Migrator
{

    public static function add(): bool
    {
        $source = realpath(__DIR__ . '/../../templates/' . Handles::PLUGIN);

        if (!is_dir(self::getDestinationFolder())) {
            echo "Copying blogify template...";
            self::copyFolder($source, self::getDestinationFolder());
            echo "Copied blogify templates";
        } else {
            echo "Blogify templates already exist. Skipping";
        }

        return true;
    }

    public static function remove(): bool
    {
        $dest = self::getDestinationFolder();

        if (is_dir($dest)) {
            self::removeDir($dest);
        }

        return true;
    }

    private static function getDestinationFolder()
    {
        return Craft::$app->getPath()->getSiteTemplatesPath() . '/' . Handles::PLUGIN;
    }

    private static function copyFolder($sourceDirectory, $destinationDirectory, $childFolder = '')
    {
        $directory = opendir($sourceDirectory);

        if (is_dir($destinationDirectory) === false) {
            mkdir($destinationDirectory);
        }

        if ($childFolder !== '') {
            if (is_dir("$destinationDirectory/$childFolder") === false) {
                mkdir("$destinationDirectory/$childFolder");
            }

            while (($file = readdir($directory)) !== false) {
                if ($file === '.' || $file === '..') {
                    continue;
                }

                if (is_dir("$sourceDirectory/$file") === true) {
                    self::copyFolder("$sourceDirectory/$file", "$destinationDirectory/$childFolder/$file");
                } else {
                    copy("$sourceDirectory/$file", "$destinationDirectory/$childFolder/$file");
                }
            }

            closedir($directory);

            return;
        }

        while (($file = readdir($directory)) !== false) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            if (is_dir("$sourceDirectory/$file") === true) {
                self::copyFolder("$sourceDirectory/$file", "$destinationDirectory/$file");
            } else {
                copy("$sourceDirectory/$file", "$destinationDirectory/$file");
            }
        }

        closedir($directory);
    }

    private static function removeDir($dir)
    {
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? self::removeDir("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }
}