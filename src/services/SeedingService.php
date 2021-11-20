<?php


namespace matfish\Blogify\services;


use Craft;
use craft\elements\Asset;
use craft\elements\Category;
use craft\elements\Entry;
use craft\elements\Tag;
use craft\elements\User;
use craft\helpers\Assets;
use craft\services\Path;
use craft\records\VolumeFolder;
use matfish\Blogify\Handles;

class SeedingService
{
    public function seed()
    {
        $tags = $this->generateTags();
        $categories = $this->generateCategories();
        $images = $this->generateImages();

        $section = Craft::$app->sections->getSectionByHandle(Handles::CHANNEL);
        $entryType = $section->getEntryTypes()[0];

        $entry = new Entry([
            'sectionId' => $section->id,
            'siteId' => Craft::$app->getSites()->getPrimarySite()->id,
            'typeId' => $entryType->id,
            'authorId' => User::find()->one()->id,
            'title' => FakerService::sentence(),
        ]);

        $entry->setFieldValue(Handles::POST_IMAGE, [FakerService::arrayElement($images)]);
        $entry->setFieldValue(Handles::POST_EXCERPT, FakerService::paragraph(3));
        $entry->setFieldValue(Handles::POST_CONTENT, FakerService::paragraphs());
        $entry->setFieldValue(Handles::POST_TAGS, FakerService::arrayElements($tags, 3));
        $entry->setFieldValue(Handles::POST_CATEGORIES, FakerService::arrayElements($categories, 2));

        Craft::$app->getElements()->saveElement($entry);
    }

    private function generateTags()
    {
        $list = ['lorem', 'ipsum', 'sid', 'amet', 'morte', 'sifur', 'janleb'];
        $tagGroup = Craft::$app->tags->getTagGroupByHandle(Handles::TAGS);
        $tags = Tag::find()->group($tagGroup)->all();

        if (count($tags) === 0) {
            foreach ($list as $tag) {
                $t = new Tag([
                    'groupId' => $tagGroup->id,
                    'title' => $tag
                ]);
                Craft::$app->elements->saveElement($t);
            }

            $tags = Tag::find()->group($tagGroup)->all();

        }

        return array_map(function ($tag) {
            return $tag->id;
        }, $tags);
    }

    private function generateCategories()
    {
        $catGroup = Craft::$app->categories->getGroupByHandle(Handles::CATEGORIES);
        $cats = Category::find()->group($catGroup)->all();

        $list = ['Sports', 'Entertainment', 'Politics', 'Finance', 'Games'];

        if (count($cats) === 0) { // Generate new categories
            foreach ($list as $category) {
                $c = new Category([
                    'groupId' => $catGroup->id,
                    'title' => $category,
                ]);
                Craft::$app->elements->saveElement($c);
            }

            $cats = Category::find()->group($catGroup)->all();
        }

        return array_map(function ($cat) {
            return $cat->id;
        }, $cats);
    }

    private function generateImages()
    {
        $assetVolume = Craft::$app->volumes->getVolumeByHandle(Handles::ASSETS);
        $assets = Asset::find()->volume($assetVolume)->all();

        // Generate new assets
        if (count($assets) === 0) {
            // Find folder
            $folder = VolumeFolder::findOne([
                'volumeId' => $assetVolume->id
            ]);

            // images in plugin folder
            $dir = realpath(__DIR__ . '/../assets/site/images');

            // temp folder in project
            $path = new Path();
            $tempDirPath = $path->getTempPath();

            for ($i=1; $i<=11; $i++) {
                $image = "img_{$i}";
                // move file from plugin assets to project temp folder
                $filename = $image . '.jpg';
                $filenameUnique = $image . '_' . rand(100, 10000) . '.jpg';
                $path = $dir . DIRECTORY_SEPARATOR . $filename;
                $tempFilePath = $tempDirPath . DIRECTORY_SEPARATOR . $filenameUnique;
                file_put_contents($tempFilePath, file_get_contents($path));


                // Upload asset to permanent folder
                // and create DB record
                $result = $this->uploadNewAsset($folder, $tempFilePath, $filenameUnique);

                $assets[] = $result;
            }
        }

        return array_map(function ($asset) {
            return $asset->id;
        }, $assets);

    }

    private function uploadNewAsset($folder, string $path, $filename)
    {
        $filename = Assets::prepareAssetName($filename);

        $asset = new Asset();
        $asset->tempFilePath = $path;
        $asset->filename = $filename;
        $asset->newFolderId = $folder->id;
        $asset->setVolumeId($folder->volumeId);
        $asset->uploaderId = User::findOne()->id;
        $asset->avoidFilenameConflicts = true;
        $asset->setScenario(Asset::SCENARIO_CREATE);
        $res = Craft::$app->getElements()->saveElement($asset);

        if (!$res) {
            blogify_log("Failed to save image");
        }

        return $asset;
    }
}