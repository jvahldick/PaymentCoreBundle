<?php

namespace JHV\Payment\CoreBundle\Operator\Connection;

/**
 * TransactionResult
 * 
 * @author Jorge Vahldick <jvahldick@gmail.com>
 * @license Please view /Resources/meta/LICENCE
 * @copyright (c) 2013
 */
interface ResultInterface
{
    
    const CONNECTION_RESULT_FAILED = 1;
    const CONNECTION_RESULT_COMPLETED = 2;
    const CONNECTION_RESULT_UNKNOWN = 3;
    
    /**
     * Localizar o pagamento.
     * Tentará localiar o pagamento registrado de retorno a transação.
     * 
     * @return \JHV\Payment\CoreBundle\Operation\DebitInterface
     */
    function getDebit();
    
    /**
     * Localiar o "estorno".
     * Verificará qual o crédito associado com a transação
     * 
     * @return null|\JHV\Payment\CoreBundle\Operation\CreditInterface
     */
    function getCredit();
    
    /**
     * Localizar a instrução de pagamento.
     * Método no qual verificará a instrução de pagamento para conexão.
     * 
     * @return \JHV\Payment\CoreBundle\Instruction\PaymentInstructionInterface
     */
    function getInstruction();
    
    /**
     * Localizar transação.
     * Verifica a transação e retornado por resultado.
     * 
     * @return \JHV\Payment\CoreBundle\Financial\TransactionInterface
     */
    function getTransaction();
    
    /**
     * Localizar o status do resultado quanto a conexão 
     * junto a operadora de serviços.
     * 
     * @return integer
     */
    function getStatus();
    
}