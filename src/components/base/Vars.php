<?php

namespace yh\mdc\components\base;

class Vars
{
    const AUTO = 'auto';
    const EXTRA_SMALL = 'extra_small';
    const SMALL = 'small';
    const MEDIUM = 'medium';
    const LARGE = 'large';

    private static array $clsCmpHeight = [
        self::AUTO => '',
        self::EXTRA_SMALL => 'mdc-cmp-height__extra-small',
        self::SMALL => 'mdc-cmp-height__small',
        self::MEDIUM => 'mdc-cmp-height__medium',
        self::LARGE => 'mdc-cmp-height__large'
    ];

    public static function getCmpHeight(string $constNameCmpHeight): string 
    {
        return self::$clsCmpHeight[$constNameCmpHeight];
    }
}
