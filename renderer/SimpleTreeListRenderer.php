<?php

namespace Wame\ListControl\Renderer;

use Nette\InvalidArgumentException;
use Wame\ComponentModule\Helpers\Helpers;
use Wame\Core\Components\BaseControl;
use Wame\ListControl\Components\ListControl;

class SimpleTreeListRenderer implements IListRenderer
{

    const
        PARAM_TREE_CONTAINER = 'treeContainer',
        TREE_CONTAINER_DEFAULT = [
            'tag' => 'ul'
        ],
        PARAM_TREE_ITEM_CONTAINER = 'treeItemContainer',
        TREE_ITEM_CONTAINER_DEFAULT = [
            'tag' => 'li'
        ];

    /**
     * Provides complete list rendering.
     * 
     * @param ListControl $listControl
     * @return string
     */
    function render($listControl)
    {
        $treeContainer = Helpers::getContainer($listControl, self::TREE_CONTAINER_DEFAULT, self::PARAM_TREE_CONTAINER);
        $treeItemContainer = Helpers::getContainer($listControl, self::TREE_ITEM_CONTAINER_DEFAULT, self::PARAM_TREE_ITEM_CONTAINER);

        $components = $listControl->getListComponents();

        if (!is_array($components)) {
            $e = new InvalidArgumentException("List has to return array of components.");
            $e->components = $components;
            throw $e;
        }

        if ($components) {
            $this->renderComponents($components, $treeContainer, $treeItemContainer);
        } else {
            $noItems = $listControl->getComponent('noItems', FALSE);
            if ($noItems) {
                $noItems->render();
            }
        }
    }

    private function renderComponents($components, $treeContainer, $treeItemContainer)
    {
        Helpers::renderContainerStart($treeContainer);

        foreach ($components as $componentNode) {

            $component = $componentNode->getComponent();

            Helpers::renderContainerStart($treeItemContainer);

            if ($component instanceof BaseControl) {
                $component->willRender("render");
            } else {
                $component->render();
            }

            if ($componentNode->childNodes) {
                $this->renderComponents($componentNode->childNodes, $treeContainer, $treeItemContainer);
            }

            Helpers::renderContainerEnd($treeItemContainer);
        }

        Helpers::renderContainerEnd($treeContainer);
    }

    /**
     * @return boolean Whenever this renderer requires to call template->render()
     */
    public function doesUseTemplateRender()
    {
        return false;
    }
}
