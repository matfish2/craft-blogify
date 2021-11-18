<?php


namespace matfish\Blogify;


use craft\web\AssetBundle;

class BlogifyAssetBundle extends AssetBundle
{
    public function init()
    {
        // define the path that your publishable resources live
        $this->sourcePath = '@matfish/Blogify/assets/site';

        // define the dependencies
        $this->depends = [
        ];

        // define the relative path to CSS/JS files that should be registered with the page
        // when this asset bundle is registered
        $this->js = [
            'js/jquery-3.3.1.min.js',
            'js/jquery-migrate-3.0.1.min.js',
            'js/jquery-ui.js',
            'js/popper.min.js',
            'js/bootstrap.min.js',
            'js/owl.carousel.min.js',
            'js/jquery.stellar.min.js',
            'js/jquery.countdown.min.js',
            'js/jquery.magnific-popup.min.js',
            'js/bootstrap-datepicker.min.js',
            'js/aos.js',
            'js/slick.min.js',
            'js/mediaelement-and-player.min.js',
            'js/main.js'
        ];

        $this->css = [
            'fonts/icomoon/style.css',
            'css/bootstrap.min.css',
            'css/magnific-popup.css',
            'css/jquery-ui.css',
            'css/owl.carousel.min.css',
            'css/owl.theme.default.min.css',
            'css/bootstrap-datepicker.css',
            'fonts/flaticon/font/flaticon.css',
            'css/aos.css',
            'css/style.css'
        ];

        parent::init();
    }
}