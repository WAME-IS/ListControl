<?php

namespace Wame\ListControl\Components;

use Nette\NotImplementedException;

interface ISimpleEmptyListControlFactory
{

    /**
     * @return SimpleEmptyListControl
     */
    public function create();
}

class SimpleEmptyListControl extends EmptyListControl
{

    protected function create()
    {
        new NotImplementedException("Special EmptyListControl supporting creating of new entities.");
    }
}
