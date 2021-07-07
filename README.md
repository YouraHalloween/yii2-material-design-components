# Components developed by Google. Transferred them to php.

The components are developed by Google. Transferred them to php. It is possible to use the ActiveField and ActiveForm components in the yii2 framework

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist youra-halloween/yii2-material-design-components "^0.2.0"
```

or add

```
"youra-halloween/yii2-material-design-components": "^0.2.0"
```

to the require section of your `composer.json` file.

## Usage

Once the extension is installed, simply use it in your code by :

```php
/**
 * Main backend application asset bundle.
 */
class MainAsset extends AssetBundle
{
    public $depends = [
        'yh\mdc\assets\YhAsset',
    ];
}
```

If you use **YhAsset**, then **JS** and **CSS** scripts are included. Separately **JS** can be connected using **MdcJsAsset**.

**UtilsAsset** will connect scripts for working with **forms** and **tables** and asynchronous xhr requests.
