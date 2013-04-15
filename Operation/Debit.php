<?php

namespace JHV\Payment\CoreBundle\Operation;

use JHV\Payment\CoreBundle\Operation\Model\Operation;
use JHV\Payment\CoreBundle\Financial\TransactionInterface;

/**
 * Debit
 * 
 * @author Jorge Vahldick <jvahldick@gmail.com>
 * @license Please view /Resources/meta/LICENCE
 * @copyright (c) 2013
 */
class Debit extends Operation implements DebitInterface
{
    
    protected $refundedAmount;
    protected $expired;
    protected $expirationDate;
    
    public function __construct()
    {
        parent::__construct();
        $this->state            = self::STATE_NEW;
        $this->refundedAmount   = 0.00;
        $this->expired          = false;
    }
    
    
    public function addTransaction(TransactionInterface $transaction)
    {
        $this->transactions[] = $transaction;
        $transaction->setDebit($this);
        
        return $this;
    }

    public function getRefundedAmount()
    {
        return $this->refundedAmount;
    }

    public function setRefundedAmount($refundedAmount)
    {
        $this->refundedAmount = $refundedAmount;
        return $this;
    }

    public function isExpired()
    {
        return $this->expired;
    }
    
    public function setExpired($boolean)
    {
        $this->expired = $boolean;
        return $this;
    }

    public function setExpirationDate(\DateTime $datetime)
    {
        $this->expirationDate = $datetime;
        return $this;
    }
    
    public function getExpirationDate()
    {
        return $this->expirationDate;
    }
    
}