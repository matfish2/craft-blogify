<?php

function blogify_log($message, $type = \yii\log\Logger::LEVEL_INFO)
{
    echo '> ' . $message . PHP_EOL;
    Craft::getLogger()->log($message, $type, 'blogify');
}

function blogify_cache($handle, Closure $callback)
{
    return \Craft::$app->cache->getOrSet($handle, function () use ($handle, $callback) {
        return $callback($handle);
    }, 60 * 60 * 24 * 365);
}