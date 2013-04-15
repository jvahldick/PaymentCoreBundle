<?php

namespace JHV\Payment\CoreBundle\Financial;

use JHV\Payment\CoreBundle\Operation\Model\OperationInterface;
use JHV\Payment\CoreBundle\Operation\DebitInterface;
use JHV\Payment\CoreBundle\Operation\CreditInterface;

/**
 * Transaction
 * 
 * @author Jorge Vahldick <jvahldick@gmail.com>
 * @license Please view /Resources/meta/LICENCE
 * @copyright (c) 2013
 */
class Transaction implements TransactionInterface
{
    
    protected $debit;
    protected $credit;
    protected $requestedAmount;
    protected $processedAmount;
    protected $transactionId;
    protected $operationType;
    protected $status;
    protected $returnedData;
    protected $createdAt;
    protected $updatedAt;
    
    public function __construct()
    {
        $this->requestedAmount  = 0.00;
        $this->processedAmount  = 0.00;
        $this->status           = self::STATUS_PENDING;
        $this->returnedData     = array();
    }
    
    public function getCreatedAt()
    {
        return $this->updatedAt;
    }
    
    public function getCredit()
    {
        return $this->credit;
    }

    public function getDebit()
    {
        return $this->debit;
    }
    
    public function getOperationType()
    {
        return $this->operationType;
    }

    public function getProcessedAmount()
    {
        return $this->processedAmount;
    }

    public function getRequestedAmount()
    {
        return $this->requestedAmount;
    }

    public function getReturnedData()
    {
        return $this->returnedData;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getTransactionId()
    {
        return $this->transactionId;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    
    public function setCredit(CreditInterface $credit)
    {
        $this->credit = $credit;
        return $this;
    }

    public function setDebit(DebitInterface $debit)
    {
        $this->debit = $debit;
        return $this;
    }

    public function setOperationType($operationType)
    {
        $this->operationType = $operationType;
        return $this;
    }

    public function setProcessedAmount($amount)
    {
        $this->processedAmount = $amount;
        return $this;
    }

    public function setRequestedAmount($amount)
    {
        $this->requestedAmount = $amount;
        return $this;
    }

    public function setReturnedData(array $data)
    {
        $this->returnedData = $data;
        return $this;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;
        return $this;
    }
    
    public function getOperation()
    {
        return (null !== $this->credit) ? $this->getCredit() : $this->getDebit();
    }
    
    public function executeOnPrePersist()
    {
        $this->createdAt = new \DateTime('UTC');
        $this->updatedAt = new \DateTime('UTC');
        return $this;
    }
    
    public function executeOnPreUpdate()
    {
        $this->updatedAt = new \DateTime('UTC');
    }
    
}