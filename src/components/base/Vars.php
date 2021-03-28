<?php

namespace yh\mdc\components\base;

class Vars
{
    const CMP_HEIGHT_AUTO = 0;
    const CMP_HEIGHT_EXTRA_SMALL = 1;
    const CMP_HEIGHT_SMALL = 2;
    const CMP_HEIGHT_MEDIUM = 3;
    const CMP_HEIGHT_LARGE = 4;

    private static array $clsCmpHeight = [
        self::CMP_HEIGHT_AUTO => '',
        self::CMP_HEIGHT_EXTRA_SMALL => 'mdc-cmp-height__extra-small',
        self::CMP_HEIGHT_SMALL => 'mdc-cmp-height__small',
        self::CMP_HEIGHT_MEDIUM => 'mdc-cmp-height__medium',
        self::CMP_HEIGHT_LARGE => 'mdc-cmp-height__large'
    ];

    public static function getCmpHeight(int $constNameCmpHeight): string 
    {
        return self::$clsCmpHeight[$constNameCmpHeight];
    }
}
