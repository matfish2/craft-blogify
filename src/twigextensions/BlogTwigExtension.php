<?php

namespace matfish\Blogify\twigextensions;

use craft\elements\Category;
use craft\elements\db\EntryQuery;
use craft\elements\Entry;
use craft\elements\Tag;
use matfish\Blogify\Handles;
use matfish\Blogify\services\IdsService;
use matfish\Blogify\services\PostViewsService;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFunction;

class BlogTwigExtension extends AbstractExtension implements GlobalsInterface
{

    public function getGlobals() : array
    {
        $idsService = new IdsService();

        return [
            'blogify' => [
                'categories' => Category::find()->groupId($idsService->categoriesGroupId()),
                'usedCategories' => $this->getUsedCategories(),
                'tags' => Tag::find()->groupId($idsService->tagsGroupId())
            ]
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('blogifyRecentPosts', [$this, 'getRecentPosts']),
            new TwigFunction('blogifySearch', [$this, 'search']),
            new TwigFunction('blogifyPopularPosts', [$this, 'getPopularPosts'])
        ];
    }

    public function getRecentPosts(): EntryQuery
    {
        return Entry::find()
            ->section(Handles::CHANNEL)
            ->orderBy('postDate desc');
    }

    public function getPopularPosts($excludeNoViews = false): EntryQuery
    {
        (new PostViewsService())->verifyEnabled();

        $q = Entry::find()
            ->section(Handles::CHANNEL)
            ->orderBy(Handles::POST_VIEWS . ' desc');

        if ($excludeNoViews) {
            $q = $q->blogifyPostViews('>0');
        }

        return $q;
    }

    public function search($query)
    {
        return Entry::find()
            ->section(Handles::CHANNEL)
            ->search('"' . $query . '"');
    }

    private function getUsedCategories()
    {
        $entries = Entry::find()->section(Handles::CHANNEL);

        return Category::find()
            ->groupId((new IdsService())->categoriesGroupId())
            ->relatedTo($entries);
    }
}