<?php

namespace Wame\ComponentModule\Renderer;

use Nette\InvalidArgumentException;
use Wame\ComponentModule\Helpers\Helpers;
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

        if (!is_array($components)) {
            $e = new InvalidArgumentException("List has to return array of components.");
            $e->components = $components;
            throw $e;
        }

        $listControl->template->hasComponents = boolval($components);
        $listControl->template->listContainer = Helpers::getContainer($listControl, SimpleListRenderer::LIST_CONTAINER_DEFAULT, SimpleListRenderer::PARAM_LIST_CONTAINER);
        $listControl->template->listItemContainer = Helpers::getContainer($listControl, SimpleListRenderer::LIST_ITEM_CONTAINER_DEFAULT, SimpleListRenderer::PARAM_LIST_ITEM_CONTAINER);
        $listControl->template->listComponents = $components;
    }

    /**
     * @return boolean Whenever this renderer requires to call template->render()
     */
    public function doesUseTemplateRender()
    {
        return true;
    }
}
