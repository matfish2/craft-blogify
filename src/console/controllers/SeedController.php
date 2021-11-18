<?php

namespace matfish\Blogify\console\controllers;

use craft\console\Controller;
use matfish\Blogify\services\SeedingService;

class SeedController extends Controller
{
    public $count = 10;

    public function options($actionID)
    {
        return ['count'];
    }

    public function actionIndex()
    {
        blogify_log("Seeding {$this->count} records...");
        for ($i = 1; $i <= $this->count; $i++) {
            blogify_log("Seeding record no. {$i}...");
            (new SeedingService())->seed();
        }

        echo "Done!";
    }
}