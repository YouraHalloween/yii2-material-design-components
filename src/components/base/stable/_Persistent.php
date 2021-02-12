<?php

namespace yh\mdc\components\base\stable;

class _Persistent
{
    /**
     * @var string PATH_VIEW - папка для View
     */
    const PATH_VIEW = '@yh/mdc/views/';

    protected array $options = [
        'class' => []
    ];

    private bool $hasInitOptions = false;
    
    public function __construct(array $property = [], array $options = [])
    {
        if (!empty($property)) {
            $this->setProperty($property);
        }
        if (!empty($options)) {
            $this->setOptions($options);
        }
    }

    /**
     * Options initialization
     */
    protected function initOptions(): void
    {
        //Сделать из строки массив, чтобы была возможность в наследуемых классах добавлять class
        if (is_string($this->options['class'])) {
            $this->options['class'] = [$this->options['class']];
        }
    }

    /**
     * Свойства компонента
     */
    public function setProperty(array $property): _Persistent
    {
        foreach ($property as $key => $value) {
            $this->$key = $value;
        }
        return $this;
    }

    /**
     * DOM options для контейнера
     * @param array $options - wrap options
     */
    public function setOptions(array $options): _Persistent
    {
        $this->options = array_merge($this->options, $options);
        
        return $this;
    }

    public function getOptions(): array
    {
        if (!$this->hasInitOptions) {
            $this->initOptions();
            $this->hasInitOptions = true;
        }
        
        return $this->options;
    }

    /**
     * Render some view from path $this->pathView
     * @param string $nameView - Name View
     */
    public function renderView(string $nameView): string
    {
        return \Yii::$app->getView()->renderFile(self::PATH_VIEW.$nameView.'.php');
    }

    /**
     * Component initialization
     */
    public function renderComponent(): string
    {
        // $this->initOptions();
        return '';
    }
}
