<?php

namespace Wame\ListControl\Components;

use Nette\ComponentModel\IContainer;
use Nette\DI\Container;
use Wame\Core\Repositories\BaseRepository;

interface IRepositoryListControl
{
    /** @return RepositoryListControl */
    public function create($repository);
}

class RepositoryListControl extends ProvidedListControl
{

    /**
     * 
     * @param Container $container
     * @param BaseRepository $repository
     * @param IContainer $parent
     * @param string $name
     */
    public function __construct(Container $container, $repository, IContainer $parent = NULL, $name = NULL)
    {
        parent::__construct($container, $parent, $name);
        $this->setProvider(new RepositoryListProvider($repository));
    }
}
