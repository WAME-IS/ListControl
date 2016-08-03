<?php

namespace Wame\ListControl\Components;

interface IEntityControlFactory
{

    /**
     * @param mixed $entity
     */
    public function create($entity);
}
