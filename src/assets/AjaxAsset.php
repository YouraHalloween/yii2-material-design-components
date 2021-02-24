<?php

namespace yh\mdc\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AjaxAsset extends AssetBundle
{
    public $sourcePath = '@yh/mdc/assets/utils/dist';
    public $js = [
        'ajax.min.js'
    ];
}
