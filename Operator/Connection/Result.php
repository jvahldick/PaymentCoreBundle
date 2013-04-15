<?php

namespace JHV\Payment\CoreBundle\Operator\Connection;

use JHV\Payment\CoreBundle\Financial\TransactionInterface;
use JHV\Payment\CoreBundle\Operation\Model\OperationInterface;

/**
 * Result
 * 
 * @author Jorge Vahldick <jvahldick@gmail.com>
 * @license Please view /Resources/meta/LICENCE
 * @copyright (c) 2013
 */
class Result implements ResultInterface
{
    
    protected $credit;
    protected $debit;
    protected $instruction;
    protected $transaction;
    protected $status;
    
    public function __construct(TransactionInterface $transaction, $status)
    {
        $this->transaction = $transaction;
        
        if (OperationInterface::OPERATION_TYPE_DEBIT === $transaction->getOperationType()) {
            $this->debit = $transaction->getDebit();
        } else {
            $this->credit = $transaction->getCredit();
            if (null !== $this->credit->getDebit()) {
                $this->debit = $this->credit->getDebit();
            }
        }
        
        $this->instruction = $transaction->getOperation()->getInstruction();
        $this->status = $status;
    }
    
    public function getCredit()
    {
        return $this->credit;
    }
    
    public function getDebit()
    {
        return $this->debit;
    }
    
    public function getInstruction()
    {
        return $this->instruction;
    }
    
    public function getTransaction()
    {
        return $this->transaction;
    }
    
    public function getStatus()
    {
        return $this->status;
    }
    
}