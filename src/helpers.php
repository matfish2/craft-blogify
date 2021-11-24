<?php

function blogify_log($message)
{
    echo '> ' . $message . PHP_EOL;
}

function blogify_cache($handle, Closure $callback)
{
    return \Craft::$app->cache->getOrSet($handle, function () use ($handle, $callback) {
        return $callback($handle);
    }, 60 * 60 * 24 * 365);
}