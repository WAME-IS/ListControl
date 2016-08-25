<?php

namespace Wame\ListControl\Components;

use ComponentModule\Renderer\TemplateListRenderer;
use Nette\ComponentModel\IComponent;
use Wame\Core\Components\BaseControl;
use Wame\ListControl\Renderer\IListRenderer;

abstract class ListControl extends BaseControl
{

    /** @var IListRenderer */
    private $renderer;

    /**
     * Get all displayed components. They have to be in [id => component] format.
     * 
     * @return IComponent[]
     */
    public abstract function getListComponents();

    /**
     * Get single displayed component by id.
     * 
     * Note, this function cannot throw an exception.
     * 
     * @return IComponent
     */
    public abstract function getListComponent($id);

    /**
     * Get component used if there are no items.
     * 
     * @return IComponent
     */
    public abstract function createComponentNoItems();

    public function render()
    {
        $this->getRenderer()->render($this);
    }

    protected function componentRender()
    {
        if ($this->getRenderer()->doesUseTemplateRender()) {
            parent::componentRender();
        }
    }

    protected function createComponent($id)
    {
        $component = $this->getListComponent($id);
        if ($component) {
            $component->setParent($this, $id);
            return $component;
        } else {
            return parent::createComponent($id);
        }
    }

    /**
     * Gets renderer used to render components in list
     * 
     * @return IListRenderer
     */
    public function getRenderer()
    {
        if (!$this->renderer) {
            $this->renderer = new TemplateListRenderer();
        }
        return $this->renderer;
    }

    /**
     * Sets renderer used to render components in list
     * 
     * @param IListRenderer $renderer
     */
    public function setRenderer(IListRenderer $renderer)
    {
        $this->renderer = $renderer;
    }
}
