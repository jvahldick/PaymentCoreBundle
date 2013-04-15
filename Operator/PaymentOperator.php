<?php

namespace JHV\Payment\CoreBundle\Operator;

use JHV\Payment\CoreBundle\Exception\InvalidProcessException;
use JHV\Payment\CoreBundle\Exception\InvalidPaymentInstructionException;
use JHV\Payment\CoreBundle\Instruction\PaymentInstructionInterface;
use JHV\Payment\CoreBundle\Operation\DebitInterface;
use JHV\Payment\CoreBundle\Operation\Model\OperationInterface;
use JHV\Payment\CoreBundle\Financial\TransactionInterface;
use JHV\Payment\CoreBundle\Operator\Connection\ResultInterface;

/**
 * PaymentOperator
 * 
 * @author Jorge Vahldick <jvahldick@gmail.com>
 * @license Please view /Resources/meta/LICENCE
 * @copyright (c) 2013
 */
abstract class PaymentOperator implements PaymentOperatorInterface
{
    
    /**
     * Efetuar a criação da transação.
     * O método receberá a operação que requer a transação, definindo assim
     * o tipo de operação e efetuando a criação deste, retornando a transação
     * já associada.
     * 
     * @return \JHV\Payment\CoreBundle\Financial\TransactionInterface
     */
    abstract protected function createTransaction(OperationInterface $operation, $amount);
    
    /**
     * Construir o resultado da conexão.
     * Criará um novo objeto chamado Result com dados da operação.
     * 
     * @return \JHV\Payment\CoreBundle\Operator\Connection\ResultInterface
     */
    abstract protected function buildConnectionResult(TransactionInterface $transaction, $status);
    
    /**
     * Localizar plugin.
     * Efetua a localização do plugin através do nome identificador.
     * 
     * @return \JHV\Payment\ServiceBundle\Plugin\PluginInterface
     */
    abstract public function findPlugin($name);
    
    /**
     * Localizar meio de pagamento.
     * Efetua a busca do meio de pagamento através do nome identificador.
     * 
     * @return \JHV\Payment\ServiceBundle\Model\PaymentMethodInterface
     */
    abstract public function findPaymentMethod($name);
    
    public function doAuthorizeCapture(DebitInterface $debit, $amount)
    {
        // Localiza a instrução de pagamento para o débito em questão
        $instruction = $debit->getInstruction();
        
        // A instrução de pagamento nunca poderá executar mais de uma transação enquanto houver pendências
        if (true === $instruction->hasPendingTransaction()) {
            throw new InvalidPaymentInstructionException(sprintf(
                'The payment instruction cannot be pending transactions'
            ));
        }
        
        // Se a instrução de pagamento for nova
        if (PaymentInstructionInterface::STATE_NEW === $instruction->getState()) {
            $transaction = $this->createTransaction($debit, $amount);
        } 
        // Senão verifica se a instrução de pagamento está em aberto
        else if (PaymentInstructionInterface::STATE_OPENED === $instruction->getState()) {
            // Caso a instrução de pagamento esteja em aberto, poderei continuar somente se o pagamento informado estiver autorizado
            if (DebitInterface::STATE_AUTHORIZED !== $debit->getState()) {
                throw new InvalidProcessException(sprintf(
                    'The payment instruction "ID: %s" is opened, but the debit is not marked as authorized, having the state "%s", and needed state "%s"',
                    $instruction->getId(),
                    $debit->getState(),
                    DebitInterface::STATE_AUTHORIZED
                ));
            }
            
            // Em situação do pagamento estar autorizado, deverá localizar a última transação realizada com sucesso
            $referenceTransaction = $debit->getLastSuccessfulTransaction();
            
            // Criar a transação e definir o ID de transação original
            $transaction = $this->createTransaction($debit, $amount);
            $transaction->setTransactionId($referenceTransaction->getTransactionId());
        }
        // Em caso de nenhuma das anteriores, o estado é inválido para seguir a transação
        else {
            throw new InvalidPaymentInstructionException(sprintf(
                'The current payment instruction state "%s" is invalid to authorize and capture execution.',
                $instruction->getState()
            ));
        }
        
        // Localizar o plugin para instrução de pagamento e o meio de pagamento
        $plugin = $this->findPlugin($instruction->getServiceName());
        $paymentMethod = $this->findPaymentMethod($instruction->getPaymentMethod());
        
        // Efetuar transação junto a operadora
        try {
            if (DebitInterface::STATE_AUTHORIZED === $debit->getState()) {
                $plugin->capture($transaction, $paymentMethod);
            } else {
                $plugin->authorizeCapture($transaction, $paymentMethod);
            }
            
            // Verifica o status de retorno da conexão junto a operadora
            if (TransactionInterface::STATUS_SUCCESS === $transaction->getStatus()) {
                $debit->setState(DebitInterface::STATE_CAPTURED);
                $debit->setProcessedAmount($transaction->getProcessedAmount());                
                $instruction->setProcessedAmount($instruction->getProcessedAmount() + $debit->getProcessedAmount());

                return $this->buildConnectionResult($transaction, ResultInterface::CONNECTION_RESULT_COMPLETED);
            } else {
                $debit->setState(DebitInterface::STATE_FAILED);
                $debit->setProcessedAmount(0.0);
                
                return $this->buildConnectionResult($transaction, ResultInterface::CONNECTION_RESULT_FAILED);
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    public function doAuthorization(DebitInterface $debit, $amount)
    {
        // Localiza a instrução de pagamento para o débito em questão
        $instruction = $debit->getInstruction();
        
        // A instrução de pagamento nunca poderá executar mais de uma transação enquanto houver pendências
        if (true === $instruction->hasPendingTransaction()) {
            throw new InvalidPaymentInstructionException(sprintf(
                'The payment instruction cannot be pending transactions'
            ));
        }
        
        // Se a instrução de pagamento for nova
        if (PaymentInstructionInterface::STATE_NEW === $instruction->getState() || PaymentInstructionInterface::STATE_OPENED === $instruction->getState()) {
            // Caso a instrução esteja em aberto, o pagamento deverá ser novo
            if (DebitInterface::STATE_NEW !== $debit->getState()) {
                throw new InvalidProcessException(sprintf(
                    'The payment instruction "ID: %s" is opened, but the debit is not marked as new, having the state "%s", and needed state "%s"',
                    $instruction->getId(),
                    $debit->getState(),
                    DebitInterface::STATE_NEW
                ));
            }
            
            // Criar uma nova transação
            $transaction = $this->createTransaction($debit, $amount);
        }
        // Em caso de nenhuma das anteriores, o estado é inválido para seguir a transação
        else {
            throw new InvalidPaymentInstructionException(sprintf(
                'The current payment instruction state "%s" is invalid to authorize and capture execution.',
                $instruction->getState()
            ));
        }
        
        // Localizar o plugin para instrução de pagamento e o meio de pagamento
        $plugin = $this->findPlugin($instruction->getServiceName());
        $paymentMethod = $this->findPaymentMethod($instruction->getPaymentMethod());
        
        // Efetuar transação junto a operadora
        try {
            $plugin->authorize($transaction, $paymentMethod);
            
            if (TransactionInterface::STATUS_SUCCESS === $transaction->getStatus()) {
                $debit->setState(DebitInterface::STATE_AUTHORIZED);
                $debit->setProcessedAmount(0.0);

                return $this->buildConnectionResult($transaction, ResultInterface::CONNECTION_RESULT_COMPLETED);
            } else {
                $debit->setState(DebitInterface::STATE_FAILED);
                $debit->setProcessedAmount(0.0);
                
                return $this->buildConnectionResult($transaction, ResultInterface::CONNECTION_RESULT_FAILED);
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    public function doCapture(DebitInterface $debit, $amount)
    {
        // Localiza a instrução de pagamento para o débito em questão
        $instruction = $debit->getInstruction();
        
        // A instrução de pagamento nunca poderá executar mais de uma transação enquanto houver pendências
        if (true === $instruction->hasPendingTransaction()) {
            throw new InvalidPaymentInstructionException(sprintf(
                'The payment instruction cannot be pending transactions'
            ));
        }
        
        // Se a instrução de pagamento for nova
        if (PaymentInstructionInterface::STATE_OPENED === $instruction->getState()) {
            // O pagamento deverá estar marcado como "AUTORIZADO" para realizar a captura
            if (DebitInterface::STATE_AUTHORIZED !== $debit->getState()) {
                throw new InvalidProcessException(sprintf(
                    'The payment instruction "ID: %s" is opened, but the debit is not marked as authorized, having the state "%s", and needed state "%s"',
                    $instruction->getId(),
                    $debit->getState(),
                    DebitInterface::STATE_AUTHORIZED
                ));
            }
            
            // Criar uma nova transação
            $transaction = $this->createTransaction($debit, $amount);
        }
        // Em caso de nenhuma das anteriores, o estado é inválido para seguir a transação
        else {
            throw new InvalidPaymentInstructionException(sprintf(
                'The current payment instruction state "%s" is invalid to capture execution.',
                $instruction->getState()
            ));
        }
        
        // Localizar o plugin para instrução de pagamento e o meio de pagamento
        $plugin = $this->findPlugin($instruction->getServiceName());
        $paymentMethod = $this->findPaymentMethod($instruction->getPaymentMethod());
        
        // Efetuar transação junto a operadora
        try {
            $plugin->capture($transaction, $paymentMethod);
            
            if (TransactionInterface::STATUS_SUCCESS === $transaction->getStatus()) {
                $debit->setState(DebitInterface::STATE_AUTHORIZED);
                $debit->setProcessedAmount($amount);

                $instruction->setProcessedAmount($amount);
                
                return $this->buildConnectionResult($transaction, ResultInterface::CONNECTION_RESULT_COMPLETED);
            } else {
                $debit->setState(DebitInterface::STATE_FAILED);
                $debit->setProcessedAmount(0.0);
                
                return $this->buildConnectionResult($transaction, ResultInterface::CONNECTION_RESULT_FAILED);
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    public function doRefund(DebitInterface $debit, $amount)
    {
        /** @todo implementar classe */
    }
    
}