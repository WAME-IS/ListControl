<?php

namespace ComponentModule\Renderer;

use Nette\InvalidArgumentException;
use stdClass;
use Wame\ListControl\ListControl;
use Wame\ListControl\Renderer\SimpleTreeListRenderer;

class TemplateTreeListRenderer extends SimpleTreeListRenderer
{

    /**
     * Provides complete list rendering.
     * 
     * @param ListControl $listControl
     * @return string
     */
    function render($listControl)
    {
        $components = $listControl->getListComponents();
        
        if(!is_array($components)) {
            $e = new InvalidArgumentException("List has to return array of components.");
            $e->components = $components;
            throw $e;
        }
        
        $listControl->template->hasComponents = boolval($components);
        $listControl->template->itemsContainer =  $this->getContainer($listControl, $this->defaults['items']);
        $listControl->template->itemContainer =  $this->getContainer($listControl, $this->defaults['item']);
        $listControl->template->listContainer = $this->getContainer($listControl, $this->defaults['list']);

        $listComponents = [];
        
        foreach ($components as $component) {

            $listItemContainer = $this->getContainer($component, $this->defaults['listItem']);

            $listComponentInfo = new stdClass;
            $listComponentInfo->container = $listItemContainer;
            $listComponentInfo->component = $component;
            $listComponents[] = $listComponentInfo;
        }

        $listControl->template->listComponents = $listComponents;
    }
    
    /**
     * @return boolean Whenever this renderer requires to call template->render()
     */
    public function doesUseTemplateRender()
    {
        return true;
    }
}
