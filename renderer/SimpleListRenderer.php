<?php

namespace Wame\ListControl\Renderer;

use Nette\Utils\Html;
use Wame\ComponentModule\Paremeters\Readers\ParameterReaders;
use Wame\Core\Components\BaseControl;

class SimpleListRenderer implements IListRenderer
{

    public $defaults = [
        'list' => [
            'tag' => 'div'
        ],
        'listItem' => [
            'tag' => 'div'
        ]
    ];

    /**
     * Provides complete list rendering.
     * 
     * @param \Wame\ListControl\ListControl $listControl
     * @return string
     */
    function render($listControl)
    {
        $listContainer = $this->getContainer($listControl, $this->defaults['list']);

        if ($listContainer) {
            echo $listContainer->startTag();
        }

        $components = $listControl->getListComponents();
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

    private function renderComponents($components)
    {
        foreach ($components as $component) {

            $listItemContainer = $this->getContainer($component, $this->defaults['listItem']);

            if ($listItemContainer) {
                echo $listItemContainer->startTag();
            }

            if ($component instanceof BaseControl) {
                $component->willRender("render");
            } else {
                $component->render();
            }

            if ($listItemContainer) {
                echo $listItemContainer->endTag();
            }
        }
    }

    /**
     * Get HTML container
     * 
     * @param BaseControl $control
     * @param array $defaultParams
     * @return Html
     */
    private function getContainer($control, $defaultParams)
    {
        $containerParams = $control->getComponentParameter("container", ParameterReaders::$HTML);
        $containerParams = array_replace_recursive($defaultParams, $containerParams);

        if (array_key_exists('tag', $containerParams) && $tag = $containerParams['tag']) {
            unset($containerParams['tag']);
            return Html::el($tag, $containerParams);
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
