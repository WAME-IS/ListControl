<?php

namespace Wame\ListControl\Components;

use Wame\Core\Components\IEntityControlFactory;

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

    /** @vae IEntityControlFactory */
    private $noItemsFactory;

    public function getListComponents()
    {
        $items = $this->provider->find();

        $itemsParameters = $this->getComponentParameter('itemsParameters');
        if ($itemsParameters) {
            $itemsParameters = new \Wame\ComponentModule\Paremeters\ArrayParameterSource($itemsParameters);
        }

        foreach ($items as $id => $item) {
            $component = $this->componentFactory->create($item);

            if ($itemsParameters && $component instanceof \Wame\Core\Components\BaseControl) {
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

    function getProvider()
    {
        return $this->provider;
    }

    function getComponentFactory()
    {
        return $this->componentFactory;
    }

    function getNoItemsFactory()
    {
        return $this->noItemsFactory;
    }

    function setProvider(IListProvider $provider)
    {
        $this->provider = $provider;
    }

    function setComponentFactory(IFactory $componentFactory)
    {
        $this->componentFactory = $componentFactory;
    }

    function setNoItemsFactory($noItemsFactory)
    {
        $this->noItemsFactory = $noItemsFactory;
    }
}
