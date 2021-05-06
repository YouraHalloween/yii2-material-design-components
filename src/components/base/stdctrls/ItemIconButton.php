<?php

namespace yh\mdc\components\base\stdctrls;

use yh\mdc\components\IconButton;

class ItemIconButton extends IconButton
{
    const LEADING = 'leading';
    const TRAILING = 'trailing';

    public string $tag = 'i';
    /**
     * @var string $position - позиция иконки в начале или в конце
     */
    public string $position = self::TRAILING;
}

