<?php

namespace matfish\Blogify\fieldtypes;

use Craft;

class ReadOnlyNumber extends \craft\fields\Number
{
    public function getInputHtml($value, \craft\base\ElementInterface $element = null): string
    {
        $value = $value ?? 0;

        $html = <<<EOT
<strong>{$value}</strong>
<input name="{$this->handle}" type="hidden" value="{$value}" />
EOT;

        return $html;
    }
}