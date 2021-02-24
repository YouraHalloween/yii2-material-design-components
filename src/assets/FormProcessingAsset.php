<?php

namespace yh\mdc\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class FormProcessingAsset extends AssetBundle
{
    public $sourcePath = '@yh/mdc/assets/utils/dist';
    public $css = [
        'form-processing.min.css'
    ];
    public $js = [
        'form-processing.min.js'
    ];
}
