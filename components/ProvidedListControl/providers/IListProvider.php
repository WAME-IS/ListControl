<?php

namespace Wame\ListControl\Components;

interface IListProvider
{
    /**
     * @return array [id => item]
     */
    public function find();
    
    /**
     * @return mixed
     */
    public function get($id);
    
    /**
     * @return string Class name of returned items
     */
    public function getReturnedType();
}
