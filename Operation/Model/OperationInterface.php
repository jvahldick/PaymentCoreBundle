<?php

namespace JHV\Payment\CoreBundle\Operation\Model;

use JHV\Payment\CoreBundle\Instruction\PaymentInstructionInterface;
use JHV\Payment\CoreBundle\Financial\TransactionInterface;

/**
 * OperationInterface
 * 
 * @author Jorge Vahldick <jvahldick@gmail.com>
 * @license Please view /Resources/meta/LICENCE
 * @copyright (c) 2013
 */
interface OperationInterface
{
    
    const OPERATION_TYPE_DEBIT = 1;
    const OPERATION_TYPE_CREDIT = 2;
    
    /**
     * Definir qual a instrução de pagamento para esta operação.
     * O método irá definir de qual instrução de pagamento a operação foi deriava.
     * 
     * @param \JHV\Payment\CoreBundle\Instruction\PaymentInstructionInterface $instruction
     * @return self
     */
    function setInstruction(PaymentInstructionInterface $instruction);
    
    /**
     * Localizar a instrução de pagamento no qual a operação foi derivada.
     * Retornará o objeto de instrução de pagamento.
     * 
     * @return PaymentInstructionInterface
     */
    function getInstruction();
    
    /**
     * Definição do valor visado para operação.
     * Este é o valor do pagamento.
     * 
     * @param decimal $amount
     * @return self
     */
    function setTargetAmount($amount);
    
    /**
     * Valor destinado pela operação.
     * Método efetua a verificação de qual foi o valor definido para operação.
     * 
     * @return decimal
     */
    function getTargetAmount();
    
    /**
     * Valor em processamento.
     * Definir qual o valor que se encontra em processamento.
     * 
     * @param decimal $amount
     * @return self
     */
    function setProcessingAmount($amount);
    
    /**
     * Valor em processo.
     * Verifica qual o valor que está sendo processado por momento.
     * 
     * @return decimal
     */
    function getProcessingAmount();
    
    /**
     * Definir o valor processado da operação.
     * Seria o valor depositado.
     * 
     * Débito  - Valor que foi depositado pelo cliente
     * Crédito - Valor creditado ao cliente
     * 
     * @param decimal $amount
     * @return self
     */
    function setProcessedAmount($amount);
    
    /**
     * Valor processado pela operação.
     * Método efetua a verificação de qual foi o valor executado pela operação.
     * 
     * @return decimal
     */
    function getProcessedAmount();
    
    /**
     * Definição de um estado para operação.
     * Estes estados são distintos de acordo com o modelo de operação escolhida.
     * 
     * @param integer $state
     * @return self
     */
    function setState($state);
    
    /**
     * Verifica o estado da operação.
     * O estado da operação por variar de acordo com o tipo de operação.
     * 
     * @return integer
     */
    function getState();
    
    /**
     * Buscar transações.
     * Localizará as transações referente a operação.
     * 
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    function getTransactions();
    
    /**
     * Última transação realizada com sucesso.
     * Este método efetuará a verificação da última transação marcada com 
     * status de sucesso, retornando assim esta transação.
     * 
     * @return \JHV\Payment\CoreBundle\Financial\TransactionInterface|null
     */
    function getLastSuccessfulTransaction();
    
}