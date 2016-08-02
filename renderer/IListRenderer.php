<?php

namespace Wame\ListControl\Renderer;

interface IListRenderer
{
	/**
	 * Provides complete list rendering.
     * 
     * @param \Wame\ListControl\ListControl $listControl
	 */
	function render($listControl);

    /**
     * @return boolean Whenever this renderer requires to call template->render()
     */
    function doesUseTemplateRender();
}
