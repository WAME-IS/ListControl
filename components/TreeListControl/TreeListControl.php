<?php

namespace Wame\ListControl\Components;

use ComponentModule\Renderer\TemplateTreeListRenderer;
use Wame\ListControl\Renderer\IListRenderer;

abstract class TreeListControl extends ListControl
{

    /**
     * Gets renderer used to render components in list
     * 
     * @return IListRenderer
     */
    public function getRenderer()
    {
        if (!$this->renderer) {
            $this->renderer = new TemplateTreeListRenderer();
        }
        return $this->renderer;
    }
}
