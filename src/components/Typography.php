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
    
    private static array $_labelSize = [
        Vars::SMALL => self::CAPTION,
        Vars::MEDIUM => self::SUBTITLE . 2,
        Vars::LARGE => self::SUBTITLE . 1,        
    ];

    private static function getCssClass(string $style, int $number = 0): string
    {        
        return self::_CLASSNAME . $style . ($number === 0 ? '' : $number);
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
     * Размер label для input
     * @param string $type
     * @see $_labelSize
     */
    public static function getLabelSize(string $type): string
    {
        return self::getCssClass(self::$_labelSize[$type]);
    }

    /**
     * Дополнительный текст
     * @param int $number 1-2
     */
    public static function subtitle(int $number): string
    {
        return self::getCssClass(self::SUBTITLE, $number);
    }

    /**
     * Основной текст
     * @param int $number 1-2
     */
    public static function body(int $number): string
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