<?php

namespace Wame\ListControl\Components;

use Nette\InvalidArgumentException;
use Wame\ComponentModule\Paremeters\ArrayParameterSource;
use Wame\Core\Components\BaseControl;

interface IProvidedListControl extends IEntityControlFactory
{

    /** @return ProvidedListControl */
    public function create($entity);
}

class ProvidedListControl extends ListControl
{

    /** @var IListProvider */
    private $provider;

    /** @var IEntityControlFactory */
    private $componentFactory;

    /** @var object */
    private $noItemsFactory;

    public function getListComponents()
    {
        $items = $this->provider->find();

        if (!is_array($items)) {
            $e = new InvalidArgumentException("Provider didn't return an array.");
            $e->provider = $this->provider;
            throw $e;
        }

        $itemsParameters = $this->getComponentParameter('itemsParameters');
        if ($itemsParameters) {
            $itemsParameters = new ArrayParameterSource($itemsParameters);
        }

        foreach ($items as $id => $item) {
            $component = $this->componentFactory->create($item);

            if ($itemsParameters && $component instanceof BaseControl) {
                $component->getComponentParameters()->add($itemsParameters);
            }

            $this->addComponent($component, $id);
        }
    }

    public function getListComponent($id)
    {
        $item = $this->provider->get($id);
        return $this->componentFactory->create($item);
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
}
