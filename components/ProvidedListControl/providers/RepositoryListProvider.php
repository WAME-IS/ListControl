<?php

namespace Wame\ListControl\Components;

use Wame\Core\Entities\BaseEntity;
use Wame\Core\Repositories\BaseRepository;

class RepositoryListProvider implements IListProvider
{

    /** @var BaseRepository */
    private $repository;

    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    public function find()
    {
        return $this->repository->find();
    }

    public function get($id)
    {
        return $this->repository->get(['id' => $id]);
    }

    public function getReturnedType()
    {
        return $this->repository->getEntityName();
    }
}
