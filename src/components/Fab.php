<?php

namespace yh\mdc\components;

use yh\mdc\components\ButtonBase;

class Fab extends ButtonBase
{
    // protected static string $controlJs = ComponentRegister::CONTROL_JS_FAB;

    // protected static array $clsBlockRender = [
    //     'base' => 'mdc-fab',
    //     'mini' => 'mdc-fab--mini',
    //     'extended' => 'mdc-fab--extended',
    // ];

    // protected static string $clsRipple = 'mdc-fab__ripple';
    // protected static string $clsLabel = 'mdc-fab__label';
    // protected static string $clsIcon = 'material-icons mdc-fab__icon';

    // protected static function getContent(string $label, array $property)
    // {
    //     $content = '';
    //     if ($property['ripple']) {
    //         $content .= self::getTagiRipple();
    //     }

    //     $content .= self::getTagIcon($property['icon']);

    //     $content .= self::getTagLabel($label);

    //     return $content;
    // }

    // public static function render(string $label, array $options = [], array $property = []): string
    // {
    //     if(empty($label))
    //         $options['aria-label'] = \array_key_exists('icon', $property) ? $property['icon'] : '';

    //     return parent::render($label, $property, $options);
    // }
}
