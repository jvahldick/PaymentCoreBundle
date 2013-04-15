<?php

namespace JHV\Payment\CoreBundle\Operation;

use JHV\Payment\CoreBundle\Operation\Model\Operation;
use JHV\Payment\CoreBundle\Financial\TransactionInterface;

/**
 * Credit
 * 
 * @author Jorge Vahldick <jvahldick@gmail.com>
 * @license Please view /Resources/meta/LICENCE
 * @copyright (c) 2013
 */
class Credit extends Operation implements CreditInterface
{
    
    protected $debit;
    
    public function addTransaction(TransactionInterface $transaction)
    {
        $this->transactions[] = $transaction;
        $transaction->setCredit($this);
        
        return $this;
    }
    
    public function __construct()
    {
        parent::__construct();
        $this->state = self::STATE_NEW;
    }
    
    public function getDebit()
    {
        return $this->debit;
    }

    public function setDebit(DebitInterface $debit)
    {
        $this->debit = $debit;
        return $this;
    }
    
    public function isIndependent()
    {
        return (null === $this->debit);
    }
    
}