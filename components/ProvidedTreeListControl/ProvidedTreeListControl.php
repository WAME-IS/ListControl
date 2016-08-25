<?php

namespace Wame\ListControl\Components;

use Nette\Application\UI\Control;
use Nette\InvalidArgumentException;
use Wame\ComponentModule\Paremeters\ArrayParameterSource;
use Wame\Core\Components\BaseControl;
use Wame\Utils\Tree\ITreeBuilder;
use Wame\Utils\Tree\NestedSetTreeBuilder;

interface IProvidedTreeListControl extends IEntityControlFactory
{

    /** @return ProvidedListControl */
    public function create($entity = null);
}

class ProvidedTreeListControl extends TreeListControl
{

    /** @var Control[] */
    private $listComponents;

    /** @var IListProvider */
    private $provider;

    /** @var IEntityControlFactory */
    private $componentFactory;

    /** @var object */
    private $noItemsFactory;
    
    /** @var ITreeBuilder */
    private $treeBuilder;

    /** @var \Wame\Utils\Tree\TreeNode */
    private $tree;
    
    public function getListComponents()
    {
        if ($this->listComponents) {
            return $this->listComponents;
        }

        $items = $this->provider->find();
        
        if (!is_array($items)) {
            $e = new InvalidArgumentException("Provider didn't return an array.");
            $e->items = $items;
            $e->provider = $this->provider;
            throw $e;
        }
        
        $tree = $this->getTreeBuilder()->buildTree($items);

        $itemsParameters = $this->getComponentParameter('itemsParameters');
        if ($itemsParameters) {
            $itemsParameters = new ArrayParameterSource($itemsParameters);
        }

        $this->listComponents = [];

        $this->getListComponentsItem($tree);
        
        foreach ($items as $id => $item) {
            $component = $this->componentFactory->create($item);
            
            if ($itemsParameters && $component instanceof BaseControl) {
                $component->getComponentParameters()->add($itemsParameters);
            }

            $this->listComponents[] = $component;
            $this->addComponent($component, $id);
        }

        return $this->listComponents;
    }
    
    private function getListComponentsItem($node)
    {
        
    }

    public function getListComponent($id)
    {
        $item = $this->provider->get($id);
        if ($item) {
            return $this->componentFactory->create($item);
        }
    }

    public function createComponentNoItems()
    {
        return $this->noItemsFactory->create();
    }

    /**
     * @return IListProvider
     */
    function getProvider()
    {
        return $this->provider;
    }

    /**
     * @return IEntityControlFactory
     */
    function getComponentFactory()
    {
        return $this->componentFactory;
    }

    /**
     * @return object
     */
    function getNoItemsFactory()
    {
        return $this->noItemsFactory;
    }

    /**
     * @param IListProvider $provider
     */
    function setProvider(IListProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @param IEntityControlFactory $componentFactory
     */
    function setComponentFactory(IEntityControlFactory $componentFactory)
    {
        $this->componentFactory = $componentFactory;
    }

    /**
     * @param object $noItemsFactory
     */
    function setNoItemsFactory($noItemsFactory)
    {
        $this->noItemsFactory = $noItemsFactory;
    }
    
    /**
     * Gets builder used to build trees from flat array
     * 
     * @return ITreeBuilder
     */
    public function getTreeBuilder()
    {
        if (!$this->treeBuilder) {
            $this->treeBuilder = new NestedSetTreeBuilder();
        }
        return $this->treeBuilder;
    }

    /**
     * Sets builder used to build trees from flat array
     * 
     * @param ITreeBuilder $treeBuilder
     */
    public function setTreeBuilder(ITreeBuilder $treeBuilder)
    {
        $this->treeBuilder = $treeBuilder;
    }
}
