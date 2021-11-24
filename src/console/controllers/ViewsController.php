<?php


namespace matfish\Blogify\console\controllers;

use craft\console\Controller;
use matfish\Blogify\migrations\Migrators\PostViewsMigrator;

class ViewsController extends Controller
{
    public function actionRecord()
    {
        return PostViewsMigrator::add();
    }
}