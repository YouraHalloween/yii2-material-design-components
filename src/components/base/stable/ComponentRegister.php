<?php

namespace yh\mdc\components\base\stable;

use yii\web\View;
use yii\helpers\Json;

class ComponentRegister {
    const TYPE_TEXTFIELD = 'textField';
    const TYPE_CHECKBOX = 'checkbox';
    const TYPE_BUTTON = 'button';
    const TYPE_SUBMIT = 'submit';
    const TYPE_FAB = 'fab';
    const TYPE_SNACKBAR = 'snackbar';
    const TYPE_LINEPROGRESS = 'linearProgress';
    const TYPE_MENU = 'menu';
    const TYPE_LIST = 'list';
    const TYPE_RADIO = 'radio';
    const TYPE_SPINNER = 'spinner';
    const TYPE_DRAWER = 'drawer';
    const TYPE_LEFTAPPBAR = 'leftAppBar';
    const TYPE_SELECT = 'select';
    const TYPE_DATATABLE = 'dataTable';
    const TYPE_ICONBUTTON = 'iconButton';

    private static ?View $_view = null;
    private static int  $positionRegister = View::POS_END;

    private static array $assComponent = [
        self::TYPE_BUTTON => 'button',
        self::TYPE_SUBMIT => 'button',
        self::TYPE_FAB => 'button',
    ];    

    private static function getView(): View
    {        
        if (is_null(self::$_view))
            self::$_view = \Yii::$app->getView();

        return self::$_view;
    }

    private static function getType($type): string {
        return isset(self::$assComponent[$type]) ? self::$assComponent[$type] : $type;
    }

    /**
     * Регистрация компонента JavsScript
     */
    private static function getRegisterControlsJS(
        string $id, 
        string $type, 
        array $jsProperty = [],
        string $parent = ''): string
    {    
        // htmlspecialchars($str)
        $param = ["'$id'", "'".self::getType($type)."'"];        
        $param[] = Json::encode($jsProperty);        

        if (!empty($parent)) {
            $param[] = "'".$parent."'";
        }

        return 'app.controls.add('.implode(',', $param).');';
    }

    /**
     * @param string $id - Form id
     * @param array $blockedControls
     * @return string
     */
    private static function getRegisterFormJS(string $id, array $blockedControls = []): string
    {
        $param = ["'$id'", Json::encode($blockedControls)];
        return 'app.controls.addControl(FormProcessing('.implode(',', $param).'));';
    }

    /**
     * Регистрация компонента
     * @param string $id - идентификатор компонента
     * @param string $type - константа TYPE_...
     * @param array $jsProperty - параметры по умолчанию для компонента
     * @param string $parent - необхдим для объединения группы компонентов
     */
    public static function registerControlJs(
        string $id, 
        string $type, 
        array $jsProperty = [], 
        string $parent = ''): void
    {
        self::getView()->registerJs(
            self::getRegisterControlsJS($id, $type, $jsProperty, $parent), 
            self::$positionRegister, 
            $id
        );
    }

    /**
     * Регистрация компонента
     * @param string $id - Form id
     * @param array $blockedControls 
     * @see yh\mdc\ActiveForm
     */
    public static function registerFormJs(string $id, array $blockedControls = []): void
    {
        self::getView()->registerJs(
            self::getRegisterFormJS($id, $blockedControls),
            self::$positionRegister,
            $id
        );
    }
}