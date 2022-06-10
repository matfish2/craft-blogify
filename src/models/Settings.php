<?php

namespace matfish\Blogify\models;

class Settings extends \craft\base\Model
{
    public string $matrixTemplatesPath = 'blogify/post/_matrix';
    public array $postViewsExcludeIps = [];

    public function rules() : array
    {
        return [
            [['matrixTemplatesPath'], 'required'],
            [['postViewsExcludeIps'],'array']
        ];
    }
}