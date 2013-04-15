<?php

namespace JHV\Payment\CoreBundle\Operation\Model;

use JHV\Payment\CoreBundle\Instruction\PaymentInstructionInterface;
use JHV\Payment\CoreBundle\Financial\TransactionInterface;

/**
 * Operation
 * 
 * @author Jorge Vahldick <jvahldick@gmail.com>
 * @license Please view /Resources/meta/LICENCE
 * @copyright (c) 2013
 */
abstract class Operation implements OperationInterface
{
 
    protected $instruction;
    protected $transactions;
    protected $targetAmount;
    protected $processingAmount;
    protected $processedAmount;
    protected $state;
    protected $createdAt;
    protected $updatedAt;
    
    /**
     * Construtor para inicializar os valores referentes a operação.
     * Inicialmente estes valores deverão ser definidos como 0,00
     */
    public function __construct()
    {
        $this->targetAmount = 0.00;
        $this->processingAmount = 0.00;
        $this->processedAmount = 0.00;
        $this->transactions = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Adicionar transação.
     * Método acrescentará uma transação a lista de transações da operação.
     * 
     * @param \JHV\Payment\CoreBundle\Financial\TransactionInterface $transaction
     * @return self
     */
    public abstract function addTransaction(TransactionInterface $transaction);
    
    public function getInstruction()
    {
        return $this->instruction;
    }

    public function setInstruction(PaymentInstructionInterface $instruction)
    {
        $this->instruction = $instruction;
        return $this;
    }
    
    public function getTargetAmount()
    {
        return $this->targetAmount;
    }

    public function setTargetAmount($targetAmount)
    {
        $this->targetAmount = $targetAmount;
        return $this;
    }
    
    function getProcessingAmount()
    {
        return $this->processingAmount;
    }
    
    function setProcessingAmount($amount)
    {
        $this->processingAmount = $amount;
        return $this;
    }

    public function getProcessedAmount()
    {
        return $this->processedAmount;
    }

    public function setProcessedAmount($processedAmount)
    {
        $this->processedAmount = $processedAmount;
        return $this;
    }
    
    public function getState()
    {
        return $this->state;
    }

    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }
    
    public function executeOnPrePersist()
    {
        $this->createdAt = new \DateTime('UTC');
        $this->updatedAt = new \DateTime('UTC');
    }
    
    public function executeOnPreUpdate()
    {
        $this->updatedAt = new \DateTime('UTC');
    }
    
    public function getTransactions()
    {
        return $this->transactions;
    }
    
    public function getLastSuccessfulTransaction()
    {
        $return = null;
        foreach ($this->transactions as $transaction) {
            if (TransactionInterface::STATUS_SUCCESS === $transaction->getStatus())
                $return = $transaction;
        }
        
        return $return;
    }
    
}