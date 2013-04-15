<?php

namespace JHV\Payment\CoreBundle\Listener;

use JHV\Payment\CoreBundle\Financial\TransactionInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

/**
 * TransactionListener
 * 
 * @author Jorge Vahldick <jvahldick@gmail.com>
 * @license Please view /Resources/meta/LICENCE
 * @copyright (c) 2013
 */
class TransactionListener
{
    
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof TransactionInterface) {
            $entity->executeOnPrePersist();
        }
    }
    
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof TransactionInterface) {
            $entity->executeOnPreUpdate();
            
            $entityManager  = $args->getEntityManager();
            $unitOfWork     = $entityManager->getUnitOfWork();
            $meta           = $entityManager->getClassMetadata(get_class($entity));
            
            $unitOfWork->recomputeSingleEntityChangeSet($meta, $entity);
        }
    }
    
}