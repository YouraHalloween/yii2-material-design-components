# Material Components for the php or yii2.

Components can be used in php or in conjunction with the yii2 framework.

[Demos](https://youra-h.github.io/page/yii2mdc.html)  
[MDC Template](https://github.com/youra-h/MDC-web-template)  
[MDC Google Components Page](https://github.com/material-components/material-components-web)

To generate a new template use [MDC Template](https://github.com/youra-h/MDC-web-template)  
To generate a color scheme [Color tool](https://material.io/resources/color/#!/?view.left=0&view.right=0)
## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist youra-halloween/yii2-material-design-components "^0.4.0"
```

or add

```
"youra-halloween/yii2-material-design-components": "^0.4.0"
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

## Examples

Add textfield username and password

```php
use yh\mdc\ActiveForm;
use yh\mdc\components\TextField;

$form->field($model, 'username')->textInput([
                'tabIndex' => 1,
                'autocomplete' => 'username',
                'required' => true,
                'property' => [
                    'autoFocus' => true,
                    'label' => Yii::t('backend/login', 'Введите логин'),
                    'labelTemplate' => TextField::ALIGN_TOP,
                    'labelSize' => Vars::LARGE,
                    'textSize' => Vars::LARGE,
                    'helper' => Yii::t('backend/login', 'email | логин'),
                    'height' => Vars::LARGE
                ],
            ])

$form->field($model, 'password')->passwordInput([
                'tabIndex' => 2,
                'autocomplete' => 'current-password',
                'required' => true,
                'property' => [
                    'label' => Yii::t('backend/login', 'Введите пароль'),
                    'labelTemplate' => TextField::ALIGN_TOP,
                    'labelSize' => Vars::LARGE,
                    'textSize' => Vars::LARGE,
                    'icons' => [
                        ['icon' => 'visibility', 'role' => 'button', 'toggle' => 'visibility_off']
                    ],
                    'helper' => '',
                    'height' => Vars::LARGE
            ]])

/**
 * role - can be icon or button
 * toggle - when you click on the button, change the icon
 */
'icons' => [
    ['icon' => 'visibility', 'role' => 'button', 'toggle' => 'visibility_off']
],

```

Add gray button

```php
Button::one(Yii::t('backend/login', 'Войти'), ['spinner' => Button::SP_AUTO], ['tabIndex' => 4])
    ->setOwner($form)
    ->gray()
    ->submit()
```

Add menu

```php
Menu::one([
    'id' => 'locale-menu',
    'items' => I18nLocale::getList()
])->render()

//Where I18nLocale::getList() returns an associative array

// OR

Menu::one([
    'id' => 'app-user-menu',
    'items' => [
        [
            'text' => Yii::t('backend/main/user-menu', 'Настройки профиля'),
            'separator' => true
        ],
        [
            'text' => Yii::t('backend/main-user-menu', 'Выход'),
            'href' => Url::to('main/logout'),
            'options' => [
                'data' => ['method' => 'post']
            ]
        ],
    ]
])
->render()
```

Add snackbar

```php
Snackbar::one('')
    ->setProperty(['closeButton'=> Yii::t('backend', 'Закрыть')])
    ->setId('app-snackbar')
    ->render()
```

## See the use of JS components here

- [Material-Design-Components-for-web](https://github.com/youra-h/MDC-web-template)
### By Google
- [Material Components for the web](https://github.com/material-components/material-components-web)
- [Manual packages](https://github.com/material-components/material-components-web/tree/master/packages)
- [Examples of what components look like](https://material.io/components?platform=web)
- [And](https://material-components.github.io/material-components-web-catalog/#/)
