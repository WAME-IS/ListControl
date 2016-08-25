<?php

namespace Wame\ListControl\Renderer;

use Nette\InvalidArgumentException;
use Nette\Utils\Html;
use Wame\ComponentModule\Paremeters\Readers\ParameterReaders;
use Wame\Core\Components\BaseControl;
use Wame\ListControl\Components\ListControl;

class SimpleTreeListRenderer implements IListRenderer
{

    public $defaults = [
        'list' => [
            'tag' => 'div'
        ],
        'listItem' => [
            'tag' => 'div'
        ],
        'items' => [
            'tag' => 'ul'
        ],
        'item' => [
            'tag' => 'li'
        ]
    ];

    /**
     * Provides complete list rendering.
     * 
     * @param ListControl $listControl
     * @return string
     */
    function render($listControl)
    {
        $listContainer = $this->getContainer($listControl, $this->defaults['list']);
        $itemsContainer = $this->getContainer($listControl, $this->defaults['items'], "itemsContainer");
        $itemContainer = $this->getContainer($listControl, $this->defaults['item'], "itemContainer");

        if ($listContainer) {
            echo $listContainer->startTag();
        }

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

        if ($listContainer) {
            echo $listContainer->endTag();
        }
    }

    private function renderComponents($components, $itemsContainer, $itemContainer)
    {
        $this->renderContainerStart($itemsContainer);

        foreach ($components as $component) {

            $listItemContainer = $this->getContainer($component, $this->defaults['listItem']);

            $this->renderContainerStart($itemContainer);

            $this->renderContainerStart($listItemContainer);

            if ($component instanceof BaseControl) {
                $component->willRender("render");
            } else {
                $component->render();
            }

            $this->renderContainerEnd($listItemContainer);
            
            if($component->childNodes) {
                renderComponents($component->childNodes, $itemsContainer, $itemContainer);
            }

            $this->renderContainerEnd($itemContainer);
        }

        $this->renderContainerEnd($itemsContainer);
    }

    /**
     * Get HTML container
     * 
     * @param BaseControl $control
     * @param array $defaultParams
     * @return Html
     */
    protected function getContainer($control, $defaultParams, $paramName = "container")
    {
        $containerParams = $control->getComponentParameter($paramName, ParameterReaders::$HTML);
        $containerParams = array_replace_recursive($defaultParams, $containerParams);

        if (array_key_exists('tag', $containerParams) && $tag = $containerParams['tag']) {
            unset($containerParams['tag']);
            return Html::el($tag, $containerParams);
        }
    }
    
    /**
     * @param Html $container
     * @param Control $control
     */
    private function renderContainerStart($container)
    {
        if ($container) {
            echo $container->startTag();
        }
    }

    /**
     * @param Html $container
     * @param Control $control
     */
    private function renderContainerEnd($container)
    {
        if ($container) {
            echo $container->endTag();
        }
    }

    /**
     * @return boolean Whenever this renderer requires to call template->render()
     */
    public function doesUseTemplateRender()
    {
        return false;
    }
}
