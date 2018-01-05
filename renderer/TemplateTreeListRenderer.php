<?php

namespace Wame\ComponentModule\Renderer;

use Nette\InvalidArgumentException;
use Wame\ComponentModule\Helpers\Helpers;
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

        if (!is_array($components)) {
            $e = new InvalidArgumentException("List has to return array of components.");
            $e->components = $components;
            throw $e;
        }

        $listControl->template->hasComponents = boolval($components);

        $listControl->template->treeContainer = Helpers::getContainer($listControl, SimpleTreeListRenderer::TREE_CONTAINER_DEFAULT, SimpleTreeListRenderer::PARAM_TREE_CONTAINER);
        $listControl->template->treeItemContainer = Helpers::getContainer($listControl, SimpleTreeListRenderer::TREE_ITEM_CONTAINER_DEFAULT, SimpleTreeListRenderer::PARAM_TREE_ITEM_CONTAINER);

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
