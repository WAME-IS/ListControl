<?php

namespace Wame\ListControl\Components;

abstract class ListControl extends \Wame\Core\Components\BaseControl
{

    /** @var IListRenderer */
    private $renderer;

    /**
     * Get all displayed components. They have to be in [id => component] format.
     * 
     * @return \Nette\ComponentModel\IComponent[]
     */
    public abstract function getListComponents();

    /**
     * Get single displayed component by id.
     * 
     * @return \Nette\ComponentModel\IComponent
     */
    public abstract function getListComponent($id);

    /**
     * Get component used if there are no items.
     * 
     * @return \Nette\ComponentModel\IComponent
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
        $component->setParent($this, $id);
        return $component;
    }

    /**
     * Gets renderer used to render components in list
     * 
     * @return IListRenderer
     */
    public function getRenderer()
    {
        if (!$this->renderer) {
            $this->renderer = new \ComponentModule\Renderer\TemplateListRenderer();
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
