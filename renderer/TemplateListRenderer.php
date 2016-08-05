<?php

namespace ComponentModule\Renderer;

use Nette\InvalidArgumentException;
use stdClass;
use Wame\ListControl\ListControl;
use Wame\ListControl\Renderer\SimpleListRenderer;

class TemplateListRenderer extends SimpleListRenderer
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
