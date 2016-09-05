<?php

namespace Wame\ListControl\Components;

use Nette\Application\UI\Control;
use Nette\InvalidArgumentException;
use Wame\ComponentModule\Paremeters\ArrayParameterSource;
use Wame\Core\Components\BaseControl;

interface IProvidedListControl extends IEntityControlFactory
{

    /** @return ProvidedListControl */
    public function create($entity = null);
}

class ProvidedListControl extends ListControl
{

    const PARAM_ITEM_PARAMETERS = 'itemsParameters';
    
    /** @var Control[] */
    private $listComponents;

    /** @var IListProvider */
    protected $provider;

    /** @var IEntityControlFactory */
    protected $componentFactory;

    /** @var object */
    protected $noItemsFactory;

    public function getListComponents()
    {
        if (is_array($this->listComponents)) {
            return $this->listComponents;
        }

        $items = $this->provider->find();
        
        if (!is_array($items)) {
            $e = new InvalidArgumentException("Provider didn't return an array.");
            $e->items = $items;
            $e->provider = $this->provider;
            throw $e;
        }

        $itemsParameters = $this->getComponentParameter(self::PARAM_ITEM_PARAMETERS);
        if ($itemsParameters) {
            $itemsParameters = new ArrayParameterSource($itemsParameters);
        }

        $this->listComponents = [];

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

    public function getListComponent($id)
    {
        $item = $this->provider->get($id);
        if ($item) {
            return $this->componentFactory->create($item);
        }
    }

    public function createComponentNoItems()
    {
        if(!$this->noItemsFactory) {
            throw new InvalidArgumentException("noItemsFactory has to be set ".get_class($this).".");
        }
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
}
