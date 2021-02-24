<?php

namespace yh\mdc\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class MdcAsset extends AssetBundle
{
    public $sourcePath = '@yh/mdc/assets/mdc';
    public $css = [
        'material-components-web.min.css'
    ];
    public $js = [
        'material-components-web.min.js'
    ];
}
