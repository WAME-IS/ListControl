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
        $listControl->template->treeContainer =  $this->getContainer($listControl, $this->defaults['tree'], 'treeContainer');
        $listControl->template->treeItemContainer =  $this->getContainer($listControl, $this->defaults['treeItem'], 'treeItemContainer');
        $listControl->template->listContainer = $this->getContainer($listControl, $this->defaults['list']);

        $listComponents = [];
        
        $this->prepareContainers($components);
        
        $listControl->template->listComponents = $components;
    }
    
    private function prepareContainers($components) {
        foreach($components as $componentNode) {
            $componentNode->container = $this->getContainer($componentNode->getComponent(), $this->defaults['listItem']);
            if($componentNode->childNodes) {
                $this->prepareContainers($componentNode->childNodes);
            }
        }
    }
    
    /**
     * @return boolean Whenever this renderer requires to call template->render()
     */
    public function doesUseTemplateRender()
    {
        return true;
    }
}
