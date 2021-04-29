<?php

namespace yh\mdc\assets;

use yii\web\AssetBundle;

class UtilsAsset extends AssetBundle
{
    // public $sourcePath = '@yh/mdc/assets/utils/dist';
    public $sourcePath = '@yh/mdc/assets/utils';
    public $css = [
    ];
    public $js = [
        'dist/utils.min.js',
        'data-table-processing.js'
    ];
}
