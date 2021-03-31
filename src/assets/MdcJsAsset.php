<?php

namespace yh\mdc\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class MdcJsAsset extends AssetBundle
{
    public $sourcePath = '@yh/mdc/assets/mdc';
    public $js = [
        'material-components-web.min.js'
    ];
}
