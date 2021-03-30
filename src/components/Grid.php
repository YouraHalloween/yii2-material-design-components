<?php

namespace yh\mdc\components;

class Grid
{
    const CLASSNAME = 'mdc-layout-grid';

    //type
    const INNER = '__inner';
    const CELL = '__cell';

    //mode
    const SPAN = '--span';
    const ORDER = '--order';
    const ALIGN = '--align';
    const FIXED_COLUMN = '--fixed-column-width';

    //devices
    const DESKTOP = 'desktop';
    const TABLET = 'tablet';
    const PHONE = 'phone';

    //cell position
    const TOP = 'top';
    const BOTTOM = 'bottom';
    const MIDDLE = 'middle';

    //grid position 
    const LEFT = 'left';
    const RIGHT = 'right';

    private static function getCssClass(string $type, string $mode = '', string $modifier = ''): string
    {
        return self::CLASSNAME . $type . $mode . (empty($modifier) ? '' : '-' . $modifier);
    }

    /**
     * Mandatory, for the layout grid element
     * @return mdc-layout-grid
     */
    public static function layout(): string
    {
        return self::CLASSNAME;
    }

    /**
     * Mandatory, for wrapping grid cell
     * @return mdc-layout-grid__inner
     */
    public static function inner(): string
    {
        return self::getCssClass(self::INNER);
    }

    /**
     * Mandatory, for the layout grid cell
     * @return mdc-layout-grid__cell
     */
    public static function cell(): string
    {
        return self::getCssClass(self::CELL);
    }

    /**
     * Optional, specifies the number of columns the cell spans on a type of device (desktop, tablet, phone)     
     * @param int $numberColumns - where {columns} is an integer between 1 and 12
     * @param string $devices - '' | Grid::DESKTOP | Grid::TABLET | Grid::PHONE
     * @return mdc-layout-grid__cell--span-<NUMBER_OF_COLUMNS> | 
     *  mdc-layout-grid__cell--span-<NUMBER_OF_COLUMNS>-<TYPE_OF_DEVICE>
     */
    public static function span(int $numberColumns, string $devices = ''): string
    {
        $modifier = $numberColumns . (empty($devices) ? '' : '-' . $devices);
        return self::getCssClass(self::CELL, self::SPAN, $modifier);
    }

    public static function spanDesktop(int $numberColumns): string
    {        
        return self::span($numberColumns, self::DESKTOP);
    }

    public static function spanTablet(int $numberColumns): string
    {        
        return self::span($numberColumns, self::TABLET);
    }

    public static function spanPhone(int $numberColumns): string
    {        
        return self::span($numberColumns, self::PHONE);
    }

    /**
     * Optional, specifies the order of the cell
     * By default, items are positioned in the source order. However, you can reorder them by using the 
     * mdc-layout-grid__cell--order-<INDEX> classes, where <INDEX> is an integer between 1 and 12. Please bear in mind that 
     * this may have an impact on accessibility, since screen readers and other tools tend to follow source order.
     * @param int $index - where <INDEX> is an integer between 1 and 12
     * @return mdc-layout-grid__cell--order-<INDEX>
     */
    public static function order(int $index): string
    {
        return self::getCssClass(self::CELL, self::ORDER, (string)$index);
    }

    /**
     * Optional, specifies the alignment of cell
     * Items are defined to stretch, by default, taking up the height of their corresponding row. You can switch to a 
     * different behavior by using one of the mdc-layout-grid__cell--align-<POSITION> alignment classes, where <POSITION> is 
     * one of top, middle or bottom.
     * @param string $position - Grid::TOP | Grid::BOTTOM | Grid::MIDDLE
     * @return mdc-layout-grid__cell--align-<POSITION>
     */
    public static function align(string $position): string
    {
        return self::getCssClass(self::CELL, self::ALIGN, $position);
    }

    /**
     * Optional, specifies the alignment of the whole grid
     * The grid is by default center aligned. You can add mdc-layout-grid--align-left or mdc-layout-grid--align-right
     *  modifier class to change this behavior. Note, these modifiers will have no effect when the grid already
     *  fills its container.
     * @param string $gridPosition - Grid::LEFT | Grid::RIGHT
     * @return mdc-layout-grid--align-<GRID_POSITION>
     */
    public static function gridAlign(string $gridPosition): string
    {
        return self::getCssClass('', self::ALIGN, $gridPosition);
    }

    /**
     * Optional, specifies the grid should have fixed column width
     * You can designate each column to have a certain width by using mdc-layout-grid--fixed-column-width modifier. 
     * The column width can be specified through sass map $mdc-layout-grid-column-width or css custom properties 
     * --mdc-layout-grid-column-width-{screen_size}. The column width is set to 72px on all devices by default.
     * @return mdc-layout-grid--fixed-column-width
     */
    public static function fixedColumn(): string
    {
        return self::getCssClass('', self::FIXED_COLUMN);
    }
}
