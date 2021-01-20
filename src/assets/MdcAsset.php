<?php

namespace yh\mdc\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class MdcAsset extends AssetBundle
{
    public $sourcePath = '@yh/mdc/assets';
    public $css = [
        'css/material-components-web.css'
    ];
    public $js = [
        'js/material-components-web.js'
    ];
}
