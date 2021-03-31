<?php

namespace yh\mdc\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class YhAsset extends AssetBundle
{
    public $depends = [
        'yh\mdc\assets\MdcAsset',
        'yh\mdc\assets\UtilsAsset',
    ];
}
