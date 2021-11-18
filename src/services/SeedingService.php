<?php


namespace matfish\Blogify\services;


use Craft;
use craft\elements\Asset;
use craft\elements\Category;
use craft\elements\Entry;
use craft\elements\Tag;
use craft\elements\User;
use craft\services\Path;
use craft\records\VolumeFolder;
use Faker\Factory;
use matfish\Blogify\Handles;
use yii\web\BadRequestHttpException;

class SeedingService
{
    protected $factory;

    public function __construct()
    {
        $this->factory = Factory::create();
    }

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
            'title' => $this->factory->sentence,
        ]);

        $entry->setFieldValue(Handles::POST_IMAGE, [$this->factory->randomElement($images)]);
        $entry->setFieldValue(Handles::POST_EXCERPT, $this->factory->paragraph);
        $entry->setFieldValue(Handles::POST_CONTENT, implode('<br><br>', $this->factory->paragraphs(10)));
        $entry->setFieldValue(Handles::POST_TAGS, $this->factory->randomElements($tags, 3));
        $entry->setFieldValue(Handles::POST_CATEGORIES, $this->factory->randomElements($categories, 2));

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

        if (count($cats) === 0) {
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
        $folder = VolumeFolder::findOne([
            'volumeId' => $assetVolume->id
        ]);

        $dir = realpath(__DIR__ . '/../assets/site/images');
        $path = new Path();
        $tempdir = $path->getTempPath();
        $assets = Asset::find()->volume($assetVolume)->all();

        if (count($assets) > 0) {
            return array_map(function ($asset) {
                return $asset->id;
            }, $assets);
        } else {
            $assets = [];
            $lists = ['hero_1', 'img_1', 'img_2', 'img_3', 'img_4'];
            foreach ($lists as $item) {
                $filename = $item . '.jpg';
                $path = $dir . DIRECTORY_SEPARATOR . $filename;
                $binary = file_get_contents($path);
                $tf = $tempdir . DIRECTORY_SEPARATOR . $item . rand(100, 10000) . '.jpg';
                file_put_contents($tf, $binary);
                $result = $this->uploadNewAsset($folder->id, $tf, $filename);
                $assets[] = $result->id;
            }

            return $assets;
        }

    }

    private function uploadNewAsset($folderId, string $path, $filename)
    {
        $assets = Craft::$app->getAssets();
        $folder = $assets->findFolder(['id' => $folderId]);

        if (!$folder) {
            throw new BadRequestHttpException('The target folder provided for uploading is not valid');
        }

        $asset = new Asset();
        $asset->tempFilePath = $path;
        $asset->filename = $filename;
        $asset->newFolderId = $folder->id;
        $asset->setVolumeId($folder->volumeId);
        $asset->avoidFilenameConflicts = true;
        $asset->setScenario(Asset::SCENARIO_CREATE);
        $res = Craft::$app->getElements()->saveElement($asset);

        return $asset;
    }
}