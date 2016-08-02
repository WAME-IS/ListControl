<?php

namespace ComponentModule\Renderer;

use Nette\Utils\Html;
use Wame\ComponentModule\Paremeters\Readers\ParameterReaders;
use Wame\Core\Components\BaseControl;
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
        $listControl->template->hasComponents = $listControl->hasComponents();
        $listControl->template->listContainer = $this->getContainer($listControl, $this->defaults['list']);

        $listComponents = [];
        
        foreach ($listControl->getListComponents() as $component) {

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
