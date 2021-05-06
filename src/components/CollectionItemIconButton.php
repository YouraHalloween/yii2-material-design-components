<?php

namespace yh\mdc\components;

use yh\mdc\components\base\stable\_Collection;

class CollectionItemIconButton extends _Collection
{
    public string $className = 'ItemIconButton';

    /**
     * Вернет иконку или кнопку с иконкой
     * @param string $position - 'leading', 'trailing'
     */
    protected function getTagIcons(string $position): string
    {
        if ($this->hasIcon($position)) {
            $icons = $this->$position;
            if (\is_array($icons)) {
                $countIcon = 0;
                $content = '';
                foreach ($icons as $key => $value) {
                    /**
                     *  Если строка массива состоит из leading => [
                     *  'clear', 'user'
                     *  'clear' => 'button' or 'icon',
                     *  'clear' => ['role' => 'icon', ...options],
                     *  'clear' => ['toggle' => 'undo-clear', ...options],
                     *  'clear' => [...options],
                     * ]
                     */
                    $options = [];
                    $property= [];
                    if (\is_array($value)) {
                        $options = $value;
                        $property['icon'] = $key;
                        $toggle = ArrayHelper::remove($options, 'toggle', false);
                        if ($toggle) {
                            $options['role'] = 'button';
                            $property['toggle'] = true;
                            $property['iconOn']= $toggle;
                        } else {
                            $options['role'] = ArrayHelper::getValue($value, 'role', 'icon');
                        }
                    } else {
                        /**
                         * Массив иконок может быть двух видов
                         * ['clear', 'phone']
                         * ['clear' => 'button', 'phone' => 'icon', 'user', 'visible' => 'un_visible']
                         * Если $key is int, то имя иконки берем из $value
                         */
                        if (is_int($key)) {
                            $property['icon'] = $value;
                            $options['role'] = 'icon';
                        } else {
                            $property['icon'] = $key;
                            if ($value === 'button' || $value === 'icon') {
                                $options['role'] = $value;
                            } else {
                                $options['role'] = 'button';
                                $property['toggle'] = true;
                                $property['iconOn']= $value;
                            }
                        }
                    }
                    $content .= $this->_getTagIcon($position, $property, $options);
                    $countIcon++;
                }
                // Ессли иконок больше одной, то лучше их объединить
                if ($countIcon > 1) {
                    return Html::tag('div', $content, ['class' => static::$clsIcons['group']]);
                }

                return $content;
            }
        }
        return '';
    }

    public function parse($listIcons): void
    {
        
    }

    public function render(): string
    {
        return '';
    }
}
