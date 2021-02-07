<?php

namespace yh\mdc\components;

use yh\mdc\components\base\ComponentRegister;
use yh\mdc\components\base\ControlList;
use yh\mdc\components\ListItem;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class Drawer extends ControlList
{
    protected string $cmpType = ComponentRegister::TYPE_DRAWER;
    
    private static array $clsBlock = [
        'base' => 'mdc-drawer',
        'dismissible' => 'mdc-drawer--dismissible',
        'open' => 'mdc-drawer--open'
    ];

    private static string $clsContent = 'mdc-drawer__content';

    private static array $clsHeader = [
        'base' => 'mdc-drawer__header',
        'title' => 'mdc-drawer__title',
        'subtitle' => 'mdc-drawer__subtitle',
        'icon' => 'material-icons mdc-drawer__title-icon',
        'title-with-icon' => 'mdc-drawer__title-with-icon',
        'title-with-icon-name' => 'mdc-drawer__title-name'
    ];

    public ListItem $listItem;
    //Абсолютная позиция
    public bool $dismissible = true;
    public bool $open = true;
    public string $header = '';
    public string $headerIcon = '';
    public string $headerLink = '';
    public string $subTitle = '';

    public function __construct(array $property = [], array $options = [])
    {
        parent::__construct($property, $options);
        $this->listItem = new ListItem();      
    }

    /**
     * Css классы для контейнера
     */
    public function initOptions(): void
    {
        parent::initOptions();
        $this->options['class'][] = self::$clsBlock['base'];
        if ($this->dismissible) {
            $this->options['class'][] = self::$clsBlock['dismissible'];
        }
        if ($this->open) {
            $this->options['class'][] = self::$clsBlock['open'];
        }
    }

    private function getTagHeader(): string
    {
        if (empty($this->header)) {
            return '';
        }
        $content = Html::beginTag('div', ['class' => self::$clsHeader['base']]);
        $optionsHeader = ['class' => [self::$clsHeader['title']]];
        if (!empty($this->headerIcon)) {            
            $header =  Html::tag('span', $this->headerIcon, ['class' => self::$clsHeader['icon']]);
            $header .=  Html::tag('span', $this->header, ['class' => self::$clsHeader['title-with-icon-name']]);
            $optionsHeader['class'][] = self::$clsHeader['title-with-icon'];
        } else {
            $header = $this->header;
        }

        if (!empty($this->headerLink)) {
            $header = Html::a($header, $this->headerLink, $optionsHeader);
            $optionsHeader = [];
        }

        $content .= Html::tag('h3', $header, $optionsHeader);

        if (!empty($this->headerSub)) {
            $content .= Html::tag('h6', $this->headerSub, ['class' => self::$clsHeader['subtitle']]);
        }
        $content .= Html::endTag('div');
        return $content;
    }

    /**
     * Нарисовать Snackbar
     */
    public function renderComponent(): string
    {
        $content = Html::beginTag('aside', $this->getOptions());
        $content .= $this->getTagHeader();
        $content .= Html::beginTag('div', ['class' => self::$clsContent]);
        
        $list = '';
        foreach ($this->items as $listProperty) {
            $listOptions = ArrayHelper::remove($listProperty, 'options', []);            
            $item = $this
                        ->listItem
                        ->setProperty($listProperty)                        
                        ->renderList(false);            
            $list .= Html::tag('div', $item, $listOptions);
        }
        
        $content .= $this->listItem->renderFrame($list);

        $content .= Html::endTag('div');
        $content .= Html::endTag('aside');        

        return $content;
    }
}
