<?php

namespace yh\mdc\components;

use yh\mdc\components\base\Vars;

class Typography
{
    const _CLASSNAME = 'mdc-typography--';

    const HEADLINE = 'headline';
    const SUBTITLE = 'subtitle';
    const BODY = 'body';
    const CAPTION = 'caption';
    const BUTTON = 'button';
    const OVERLINE = 'oveline';

    const CSS_FONT_SIZE = 'mdc-fs__';
    
    private static array $_fontStyle = [
        Vars::EXTRA_SMALL => self::CAPTION,
        Vars::SMALL => self::SUBTITLE . 2,
        Vars::MEDIUM => self::SUBTITLE . 1,
        Vars::LARGE => self::HEADLINE . 6,        
    ];    

    public static function getCssClass(string $style, int $number = 0): string
    {        
        return self::_CLASSNAME . $style . ($number === 0 ? '' : $number);
    }

    /**
     * Размер текста, который используется в сочетании с компонентами
     * Например label и input
     * @param string $style
     * @see $_fontStyle
     */
    public static function fontStyle(string $style): string
    {
        return self::getCssClass(self::$_fontStyle[$style]);
    }

    /**
     * Возвращает класс, который устанавливает размер текста
     * @param string $constFontSize - Vars::NORMAL | Vars::EXTRA_SMALL | Vars::SMALL | Vars::MEDIUM | Vars::LARGE
     * @return css class, example 'mdc_fs__small';
     */
    public static function fontSize(string $constFontSize): string 
    {        
        return $constFontSize === Vars::NORMAL ? '' : self::CSS_FONT_SIZE . $constFontSize;
    }

    /**
     * Заголовок
     * @param int $number 1-6
     */
    public static function headline(int $number): string
    {
        return self::getCssClass(self::HEADLINE, $number);
    }    

    /**
     * Дополнительный текст
     * @param int $number 1-2
     */
    public static function subtitle(int $number = 1): string
    {
        return self::getCssClass(self::SUBTITLE, $number);
    }

    /**
     * Основной текст
     * @param int $number 1-2
     */
    public static function body(int $number = 1): string
    {
        return self::getCssClass(self::BODY, $number);
    }

    /**
     * Самый мелкий текст     
     */
    public static function caption(): string
    {
        return self::getCssClass(self::CAPTION);
    }
}