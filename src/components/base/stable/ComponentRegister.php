<?php

namespace yh\mdc\components\base\stable;

use yii\web\View;
use yii\helpers\Json;

class ComponentRegister
{
    const TYPE_TEXTFIELD = 'textField';
    const TYPE_CHECKBOX = 'checkbox';
    const TYPE_BUTTON = 'button';
    const TYPE_SUBMIT = 'submit';
    const TYPE_FAB = 'fab';
    const TYPE_SNACKBAR = 'snackbar';
    const TYPE_LINEARPROGRESS = 'linearProgress';
    const TYPE_MENU = 'menu';
    const TYPE_LIST = 'list';
    const TYPE_RADIO = 'radio';
    const TYPE_SPINNER = 'spinner';
    const TYPE_DRAWER = 'drawer';
    const TYPE_LEFTAPPBAR = 'leftAppBar';
    const TYPE_SELECT = 'select';
    const TYPE_DATATABLE = 'dataTable';
    const TYPE_ICONBUTTON = 'iconButton';
    const TYPE_COLLAPSE = 'collapse';
    const TYPE_COLLAPSE_SEARCH = 'collapseSearch';

    /**
     * @var View $_view с помощью view выводятся javascript
     */
    private static ?View $_view = null;
    /**
     * @var int $positionRegister в начале или конце файла вывести скрипты javascript
     */
    private static int  $positionRegister = View::POS_END;

    /**
     * @var array $assComponent массив ассоциаций. Какой JavaScript класс будет инициализироваться
     * Например для self::TYPE_BUTTON и self::TYPE_SUBMIT, будет инициализироваться класс Button
     */
    private static array $assComponent = [
        self::TYPE_BUTTON => 'button',
        self::TYPE_SUBMIT => 'button',
        self::TYPE_FAB => 'button',
    ];

    /**
     * Вернуть объект yii2 View
     */
    private static function getView(): View
    {
        if (is_null(self::$_view)) {
            self::$_view = \Yii::$app->getView();
        }

        return self::$_view;
    }

    /**
     * Вернуть настоящий тип компонента
     * @param string $type
     * @return string type component
     */
    private static function getType(string $type): string
    {
        return isset(self::$assComponent[$type]) ? self::$assComponent[$type] : $type;
    }

    /**
     * Регистрация компонента JavsScript
     * @param string $id id компонента
     * @param string $type тип компонента, указывается через переменную _PersistentCmp->cmpType
     * @param array $jsProperty массив свойств создаваемого Javascript компонента
     * @param string $owner собственник компонента, можно объединять в группу несколько компонентов
     * @return string Javascript code
     */
    private static function getRegisterControlsJS(
        string $id,
        string $type,
        array $jsProperty = [],
        string $owner = ''
    ): string
    {
        // htmlspecialchars($str)
        $param = ["'$id'", "'".self::getType($type)."'"];
        $param[] = Json::encode($jsProperty);

        if (!empty($owner)) {
            $param[] = "'".$owner."'";
        }

        return 'app.controls.add('.implode(',', $param).');';
    }

    /**
     * @param string $objectDescr например new FormControl()
     * @return string Javascript code
     */
    private static function getRegisterObjectJs(string $objectDescr): string
    {
        return "app.controls.addObject($objectDescr);";
    }

    /**
     * Регистрация компонента
     * @see getRegisterControlsJS()
     */
    public static function registerControlJs(
        string $id,
        string $type,
        array $jsProperty = [],
        string $owner = ''
    ): void
    {
        self::getView()->registerJs(
            self::getRegisterControlsJS($id, $type, $jsProperty, $owner),
            self::$positionRegister,
            $id
        );
    }

    /**
     * Регистрация готового объекта
     * @param string $objectDescr например new FormControl()
     */
    public static function registerObjectJs(string $objectDescr): void
    {
        self::getView()->registerJs(
            self::getRegisterObjectJs($objectDescr),
            self::$positionRegister
            // $id
        );
    }
}
