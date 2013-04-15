<?php

namespace JHV\Payment\CoreBundle\Operation\Model;

use Doctrine\Common\Persistence\ObjectManager;

/**
 * OperationManager
 * 
 * @author Jorge Vahldick <jvahldick@gmail.com>
 * @license Please view /Resources/meta/LICENCE
 * @copyright (c) 2013
 */
abstract class OperationManager implements OperationManagerInterface
{
    
    /** @var \Doctrine\Common\Persistence\ObjectRepository $respository */
    protected $repository;
    protected $objectManager;
    protected $class;
    
    public function __construct(ObjectManager $objectManager, $operationClass)
    {
        $this->objectManager = $objectManager;
        $this->class = $operationClass;
        
        // Localizar repositório da operação
        $this->objectManager->getRepository($this->class);
    }
    
    public function create()
    {
        $entity = $this->class;
        return new $entity;
    }

    public function delete(OperationInterface $operation)
    {
        $this->objectManager->remove($operation);
        $this->flush();
    }

    public function find($id)
    {
        return $this->repository->find($id);
    }

    public function findAll()
    {
        return $this->repository->findAll();
    }

    public function findBy(array $criteria)
    {
        return $this->repository->findBy($criteria);
    }

    public function findOneBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    public function flush()
    {
        $this->objectManager->flush();
    }

    public function persist(OperationInterface $operation, $flush = true)
    {
        $this->objectManager->persist($operation);
        if (true === $flush)
            $this->flush();
    }
    
}