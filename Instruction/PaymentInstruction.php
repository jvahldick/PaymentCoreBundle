<?php

namespace JHV\Payment\CoreBundle\Instruction;

/**
 * PaymentInstruction
 * 
 * @author Jorge Vahldick <jvahldick@gmail.com>
 * @license Please view /Resources/meta/LICENCE
 * @copyright (c) 2013
 */
class PaymentInstruction implements PaymentInstructionInterface
{
    
    protected $amount;
    protected $currency;
    protected $processedAmount;
    protected $refundedAmount;
    protected $serviceName;
    protected $paymentMethod;
    protected $state;
    protected $debits;
    protected $credits;
    protected $extendedData;
    
    public function __construct($amount = 0.00, $currency = 'BRL')
    {
        $this->extendedData     = array();
        $this->state            = self::STATE_NEW;
        $this->amount           = $amount;
        $this->currency         = $currency;
        $this->processedAmount  = 0.00;
        $this->refundedAmount   = 0.00;
        $this->debits           = new \Doctrine\Common\Collections\ArrayCollection();
        $this->credits          = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }
    
    public function getCurrency()
    {
        return $this->currency;
    }
    
    public function setCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
    }

    public function getProcessedAmount()
    {
        return $this->processedAmount;
    }

    public function setProcessedAmount($amount)
    {
        $this->processedAmount = $amount;
        return $this;
    }

    public function getRefundedAmount()
    {
        return $this->refundedAmount;
    }

    public function setRefundedAmount($amount)
    {
        $this->refundedAmount = $amount;
        return $this;
    }
    
    public function getServiceName()
    {
        return $this->serviceName;
    }

    public function setServiceName($serviceName)
    {
        $this->serviceName = $serviceName;
        return $this;
    }

    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
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
    
    public function getCreditOperations()
    {
        return $this->credits;
    }
    
    public function addCreditOperation(\JHV\Payment\CoreBundle\Operation\CreditInterface $operation)
    {
        $operation->setInstruction($this);
        $this->credits[] = $operation;
        
        return $this;
    }
    
    public function getDebitOperations()
    {
        return $this->debits;
    }
    
    public function addDebitOperation(\JHV\Payment\CoreBundle\Operation\DebitInterface $operation)
    {
        $operation->setInstruction($this);
        $this->debits[] = $operation;
        
        return $this;
    }
    
    public function getExtendedData()
    {
        return $this->extendedData;
    }

    public function setExtendedData(array $data)
    {
        $this->extendedData = $data;
        return $this;
    }
    
    /**
     * @todo Criar os cálculos para exibição do saldo
     */
    public function getBalance()
    {}
    
    /**
     * @todo fazer descrição
     */
    public function hasPendingTransaction()
    {
        return null !== $this->getPendingTransaction();
    }
    
    /**
     * @todo fazer descrição
     */
    public function getPendingTransaction()
    {
        foreach ($this->getDebitOperations() as $debit) {
            if (null !== $transaction = $debit->getPendingTransaction()) {
                return $transaction;
            }
        }

        foreach ($this->getCreditOperations() as $credit) {
            if (null !== $transaction = $credit->getPendingTransaction()) {
                return $transaction;
            }
        }

        return null;
    }
    
}