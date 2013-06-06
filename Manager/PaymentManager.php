<?php

namespace JHV\Payment\CoreBundle\Manager;

use JHV\Payment\CoreBundle\Operator\PaymentOperator;
use JHV\Payment\CoreBundle\Exception\NotFoundException;
use JHV\Payment\CoreBundle\Instruction\PaymentInstructionInterface;
use JHV\Payment\CoreBundle\Operation\DebitInterface;

use JHV\Payment\CoreBundle\Financial\TransactionInterface;

use JHV\Payment\ServiceBundle\Manager\PaymentMethodManagerInterface;
use JHV\Payment\ServiceBundle\Manager\PluginManagerInterface;

use JHV\Payment\CoreBundle\Operation\Model\OperationInterface;

use Doctrine\ORM\EntityManager;

/**
 * PaymentManager
 * 
 * @author Jorge Vahldick <jvahldick@gmail.com>
 * @license Please view /Resources/meta/LICENCE
 * @copyright (c) 2013
 */
class PaymentManager extends PaymentOperator implements PaymentManagerInterface
{
  
    protected $classes;
    protected $entityManager;
    protected $pluginManager;
    protected $paymentMethodManager;
    
    public function __construct(EntityManager $entityManager, PluginManagerInterface $pluginManager, PaymentMethodManagerInterface $paymentMethodManager, array $classes)
    {
        $this->entityManager = $entityManager;
        $this->pluginManager = $pluginManager;
        $this->paymentMethodManager = $paymentMethodManager;
        $this->classes = $classes;
    }
    
    protected function buildConnectionResult(TransactionInterface $transaction, $status)
    {
        $class = $this->classes['result'];
        return new $class($transaction, $status);
    }
    
    public function createCredit(PaymentInstructionInterface $instruction, $amount, DebitInterface $debit = null, $flush = true)
    {
        $credit = new $this->classes['credit'];
        $credit->setTargetAmount($amount);
        $credit->setInstruction($instruction);
        
        if (null !== $debit)
            $credit->setDebit($debit);

        if (true === $flush) {
            $this->entityManager->persist($credit);
            $this->entityManager->flush();
        }
        
        return $credit;
    }
    
    public function createDebit(PaymentInstructionInterface $instruction, $amount, $flush = true)
    {
        $debit = new $this->classes['debit'];
        $debit->setTargetAmount($amount);
        $debit->setInstruction($instruction);

        if (true === $flush) {
            $this->entityManager->persist($debit);
            $this->entityManager->flush();
        }
        
        return $debit;
    }
    
    protected function createTransaction(OperationInterface $operation, $amount)
    {
        // Definir o tipo de operação
        $operationType  = ($operation instanceof DebitInterface) ? OperationInterface::OPERATION_TYPE_DEBIT : OperationInterface::OPERATION_TYPE_CREDIT;
        $definition     = ($operation instanceof DebitInterface) ? 'debit' : 'credit';
        
        // O valor da operação deve ser maior que zero e menor que o montante da operação
        if (1 === bccomp($amount, $operation->getTargetAmount()) || -1 !== bccomp(0, $amount)) {
            throw new \InvalidArgumentException(sprintf(
                'The value "%s" to be processed cannot be greater then payment amount "%s" and cannot be nulled or negative number',
                $amount,
                $operation->getTargetAmount()
            ));
        }
        
        // Criar e definir dados da operação
        $transaction = new $this->classes['transaction'];
        $transaction->setRequestedAmount($amount);
        $transaction->setOperationType($operationType);
        $transaction->{'set' . ucfirst($definition)}($operation);

        $operation->addTransaction($transaction);
        
        return $transaction;
    }
    
    public function createPaymentInstruction(PaymentInstructionInterface $instruction)
    {
        if (PaymentInstructionInterface::STATE_NEW !== $instruction->getState()) {
            throw new \InvalidArgumentException(sprintf(
                'The payment instruction state is %s, but to continue process the state must be %s',
                $instruction->getState(),
                PaymentInstructionInterface::STATE_NEW
            ));
        }
        
        // Persistir entidade
        $this->entityManager->persist($instruction);
        $this->entityManager->flush();
    }
    
    public function findInstruction($id)
    {
        $entity = $this->entityManager->getRepository($this->classes['instruction'])->find($id);
        if (null === $entity) {
            throw new NotFoundException(sprintf('The payment instruction "%s" was not found.', $id));
        }
        
        return $entity;
    }
    
    public function findPaymentMethod($name)
    {
        return $this->paymentMethodManager->get($name);
    }
    
    public function findPlugin($name)
    {
        return $this->pluginManager->get($name);
    }
    
    public function authorizeCapture(DebitInterface $debit, $amount)
    {
        $this->entityManager->beginTransaction();
        try {
            $result = $this->doAuthorizeCapture($debit, $amount);

            $this->entityManager->persist($result->getTransaction());
            $this->entityManager->persist($result->getDebit());
            $this->entityManager->persist($result->getInstruction());
            $this->entityManager->flush();
            $this->entityManager->commit();

            return $result;
        } catch (\Exception $failure) {
            $this->entityManager->rollback();
            $this->entityManager->close();

            throw $failure;
        }
    }
    
    public function authorize(DebitInterface $debit, $amount)
    {
        $this->entityManager->beginTransaction();
        try {
            $result = $this->doAuthorization($debit, $amount);

            $this->entityManager->persist($result->getTransaction());
            $this->entityManager->persist($result->getDebit());
            $this->entityManager->persist($result->getInstruction());
            $this->entityManager->flush();
            $this->entityManager->commit();

            return $result;
        } catch (\Exception $failure) {
            $this->entityManager->rollback();
            $this->entityManager->close();

            throw $failure;
        }
    }
    
    public function capture(DebitInterface $debit, $amount)
    {
        $this->entityManager->beginTransaction();
        try {
            $result = $this->doCapture($debit, $amount);

            $this->entityManager->persist($result->getTransaction());
            $this->entityManager->persist($result->getDebit());
            $this->entityManager->persist($result->getInstruction());
            $this->entityManager->flush();
            $this->entityManager->commit();

            return $result;
        } catch (\Exception $failure) {
            $this->entityManager->rollback();
            $this->entityManager->close();

            throw $failure;
        }
    }
    
    public function refund(DebitInterface $debit, $amount)
    {
        $this->entityManager->beginTransaction();
        try {
            $result = $this->doRefund($debit, $amount);

            $this->entityManager->persist($result->getTransaction());
            $this->entityManager->persist($result->getDebit());
            $this->entityManager->persist($result->getInstruction());
            $this->entityManager->flush();
            $this->entityManager->commit();

            return $result;
        } catch (\Exception $failure) {
            $this->entityManager->rollback();
            $this->entityManager->close();

            throw $failure;
        }
    }

    /**
     * Localizar o banco gerenciador dos pagamentos.
     *
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }
    
}