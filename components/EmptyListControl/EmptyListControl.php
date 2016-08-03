<?php

namespace Wame\ListControl\Components;

use Wame\Core\Components\BaseControl;
use Wame\Core\Exception\SecurityException;

abstract class EmptyListControl extends BaseControl
{

    /** @var boolean */
    protected $isEditable;

    public function handleCreate()
    {
        if($this->isEditable) {
            $this->create();
        } else {
            throw new SecurityException("Adding is not allowed in this control");
        }
    }

    protected function componentRender()
    {
        $this->template->isEditable = $this->isEditable;
        parent::componentRender();
    }
    
    /**
     * Create new entity.
     */
    protected abstract function create();
}
