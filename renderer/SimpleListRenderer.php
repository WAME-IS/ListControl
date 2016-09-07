<?php

namespace Wame\ListControl\Renderer;

use Nette\InvalidArgumentException;
use Wame\ComponentModule\Helpers\Helpers;
use Wame\Core\Components\BaseControl;
use Wame\ListControl\Components\ListControl;

class SimpleListRenderer implements IListRenderer
{

    const
        PARAM_LIST_CONTAINER = 'listContainer',
        LIST_CONTAINER_DEFAULT = [
        'tag' => 'div'
        ],
        PARAM_LIST_ITEM_CONTAINER = 'listItemContainer',
        LIST_ITEM_CONTAINER_DEFAULT = [
        'tag' => 'div'
    ];

    /**
     * Provides complete list rendering.
     * 
     * @param ListControl $listControl
     * @return string
     */
    function render($listControl)
    {
        $listContainer = Helpers::getContainer($listControl, self::LIST_CONTAINER_DEFAULT, self::PARAM_LIST_CONTAINER);

        Helpers::renderContainerStart($listContainer);

        $components = $listControl->getListComponents();

        if (!is_array($components)) {
            $e = new InvalidArgumentException("List has to return array of components.");
            $e->components = $components;
            throw $e;
        }

        if ($components) {
            $this->renderComponents($components);
        } else {
            $noItems = $listControl->getComponent('noItems', FALSE);
            if ($noItems) {
                $noItems->render();
            }
        }

        Helpers::renderContainerEnd($listContainer);
    }

    protected function renderComponents($components)
    {
        foreach ($components as $component) {
            $this->renderComponent($component);
        }
    }

    protected function renderComponent($component)
    {
        $listItemContainer = Helpers::getContainer($component, self::LIST_ITEM_CONTAINER_DEFAULT, self::PARAM_LIST_ITEM_CONTAINER);

        Helpers::renderContainerStart($listItemContainer);

        if ($component instanceof BaseControl) {
            $component->willRender("render");
        } else {
            $component->render();
        }

        Helpers::renderContainerEnd($listItemContainer);
    }

    /**
     * @return boolean Whenever this renderer requires to call template->render()
     */
    public function doesUseTemplateRender()
    {
        return false;
    }
}
