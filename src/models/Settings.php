<?php

namespace matfish\Blogify\models;

class Settings extends \craft\base\Model
{
    public $matrixTemplatesPath = 'blogify/post/_matrix';
    public $postViewsExcludeIps = [];

    public function rules()
    {
        return [
            [['matrixTemplatesPath'], 'required'],
            [['postViewsExcludeIps'],'array']
        ];
    }
}