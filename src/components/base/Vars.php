<?php

namespace yh\mdc\components\base;

class Vars
{
    const NORMAL = 'normal';
    const EXTRA_SMALL = 'exsmall';
    const SMALL = 'small';
    const MEDIUM = 'medium';
    const LARGE = 'large';

    /**
     * начало CSS класса отвечающего за размер (height) компонента
     */
    const CSS_CMP_HEIGHT = 'mdc-h__';

    /**
     * Возвращает класс, который устанавливает размер компонента
     * @param string $constHeight - Vars::NORMAL | Vars::EXTRA_SMALL | Vars::SMALL | Vars::MEDIUM | Vars::LARGE
     * @return css class, example 'mdc_h__small';
     */
    public static function cmpHeight(string $constHeight): string 
    {        
        return $constHeight === self::NORMAL ? '' : self::CSS_CMP_HEIGHT . $constHeight;
    }
}
