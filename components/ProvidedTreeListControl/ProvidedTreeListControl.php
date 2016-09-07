<?php

namespace Wame\ListControl\Components;

use Nette\ComponentModel\Component;
use Nette\InvalidArgumentException;
use Wame\ComponentModule\Paremeters\ArrayParameterSource;
use Wame\Core\Components\BaseControl;
use Wame\Utils\Tree\ITreeBuilder;
use Wame\Utils\Tree\NestedSetTreeBuilder;
use Wame\Utils\Tree\TreeNode;

interface IProvidedTreeListControl extends IEntityControlFactory
{

    /** @return ProvidedListControl */
    public function create($entity = null);
}

class TreeListNode
{

    /** @var int */
    public $id;

    /** @var mixed */
    public $item;

    /** @var Component */
    public $component;

    public function __construct($id, $item)
    {
        $this->id = $id;
        $this->item = $item;
    }

    function getId()
    {
        return $this->id;
    }

    function getItem()
    {
        return $this->item;
    }

    function getComponent()
    {
        return $this->component;
    }

    function setComponent(Component $component)
    {
        $this->component = $component;
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->item, $name], $arguments);
    }
}

class ProvidedTreeListControl extends TreeListControl
{
    const PARAM_HIDE_FIRST = 'hideFirst';

    /** @var IListProvider */
    private $provider;

    /** @var IEntityControlFactory */
    private $componentFactory;

    /** @var object */
    private $noItemsFactory;

    /** @var ITreeBuilder */
    private $treeBuilder;

    /** @var TreeNode */
    private $tree;

    public function getListComponents()
    {
        if ($this->tree) {
            return $this->tree;
        }

        $items = $this->provider->find();

        if (!is_array($items)) {
            $e = new InvalidArgumentException("Provider didn't return an array.");
            $e->items = $items;
            $e->provider = $this->provider;
            throw $e;
        }

        $items = array_map(function($item, $index) {
            return new TreeListNode($index, $item);
        }, $items, array_keys($items));

        $branches = $this->getTreeBuilder()->buildTree($items);
        if($this->getComponentParameter(self::PARAM_HIDE_FIRST)) {
            $this->tree = $branches->childNodes;
        } else {
            $this->tree = [$branches];
        }

        $itemsParameters = $this->getComponentParameter('itemsParameters');
        if ($itemsParameters) {
            $itemsParameters = new ArrayParameterSource($itemsParameters);
        }

        foreach ($items as $processingItem) {
            $component = $this->componentFactory->create($processingItem->getItem());

            if ($itemsParameters && $component instanceof BaseControl) {
                $component->getComponentParameters()->add($itemsParameters);
            }

            $this->addComponent($component, $processingItem->getId());
            $processingItem->setComponent($component);
        }

        return $this->tree;
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
