<?php

namespace yh\mdc\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class DataTableProcessingAsset extends AssetBundle
{
    public $sourcePath = '@yh/mdc/assets/utils';
    public $js = [
        'data-table-processing.min.js'
    ];
}
