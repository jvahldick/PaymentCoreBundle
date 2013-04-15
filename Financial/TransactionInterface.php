<?php

namespace JHV\Payment\CoreBundle\Financial;

use JHV\Payment\CoreBundle\Operation\CreditInterface;
use JHV\Payment\CoreBundle\Operation\DebitInterface;

/**
 * TransactionInterface
 * A transação a referente a cada operação requisitada junto a operadora.
 * 
 * @author Jorge Vahldick <jvahldick@gmail.com>
 * @license Please view /Resources/meta/LICENCE
 * @copyright (c) 2013
 */
interface TransactionInterface
{
    
    const STATUS_FAILED     = 1;
    const STATUS_PENDING    = 2;
    const STATUS_CANCELED   = 3;
    const STATUS_SUCCESS    = 4;
    
    /**
     * Valor requisitado.
     * Definição do montante requisitado para transação.
     * 
     * @param decimal $amount Valor requisitado para transação
     * @return self
     */
    function setRequestedAmount($amount);
    
    /**
     * Valor requisitado.
     * Verificar qual o valor requisitado para transação.
     * 
     * @return decimal
     */
    function getRequestedAmount();
    
    /**
     * Irá definir qual o valor real processado.
     * Nem sempre o valor processado será o mesmo do valor requisitado
     * inicialmente na transação.
     * 
     * Exemplo:
     * Se por um acaso um boleto é gerado, pode ocorrer do cliente
     * efetuar um pagamento parcial, portanto não será o mesmo do requisitado.
     * 
     * @param decimal $amount
     * @return self
     */
    function setProcessedAmount($amount);
    
    /**
     * Buscará qual o valor processado, no qual este valor será modificado
     * somente com o retorno do serviço.
     * 
     * @return decimal
     */
    function getProcessedAmount();
    
    /**
     * Define o tipo de operação, das quais:
     * 1. Crédito
     * 2. Débito
     * 
     * @param integer $operationType
     * @return self
     */
    function setOperationType($operationType);
    
    /**
     * Localizará qual o tipo de operação realizada para operação.
     * 
     * Este método tem o intuito de facilitar a descoberta de
     * qual modelo de operação foi relizada para esta transação.
     * 
     * @return integer
     */
    function getOperationType();
    
    /**
     * Número de identificação externa.
     * O Transaction ID é o identificador da transação junto a operadora
     * em caso de novas ações ou consultas.
     * 
     * @param string $transactionId
     * @return self
     */
    function setTransactionId($transactionId);
    
    /**
     * Identificador externo.
     * Método retornará o ID de transação para operações junto a operadora.
     * 
     * @return string
     */
    function getTransactionId();
    
    /**
     * Definir dados retornados pela operadora.
     * Este método tem o intuito de armazenar todos os parâmetros retornados
     * pela operação após a transação.
     * 
     * @param array $data
     * @return self
     */
    function setReturnedData(array $data);
    
    /**
     * Dados de retorno.
     * Localizar todos os parâmetros retornados pela operadora para transação.
     * 
     * @return array
     */
    function getReturnedData();
    
    /**
     * Definição do status da transação.
     * A transação será somente o extrato real de operações.
     * 
     * Os status são definidos como:
     * 1. STATUS_FAILED     - Falha durante a transação
     * 2. STATUS_PENDING    - A transação realizada, porém com pendências
     * 3. STATUS_CANCELED   - Transação foi cancelada
     * 4. STATUS_SUCCESS    - A transação foi completa
     * 
     * @param integer $status
     * @return self
     */
    function setStatus($status);
    
    /**
     * Status da transação.
     * Verifica o status atual da operação.
     * 
     * @return integer
     */
    function getStatus();
    
    /**
     * Data de criação.
     * Verifica quando a transação foi criada.
     * 
     * @reutrn \DateTime
     */
    function getCreatedAt();
    
    /**
     * Data de atualização.
     * Verifica qual a última modificação realizada na transação.
     * 
     * @reutrn \DateTime
     */
    function getUpdatedAt();
    
    /**
     * Definir crédito.
     * Este método associa um estorno / crédito independente a transação.
     * 
     * @param \JHV\Payment\CoreBundle\Operation\CreditInterface $credit
     * @return self
     */
    function setCredit(CreditInterface $credit);
    
    /**
     * Buscar crédito.
     * Localizar crédito / estorno associado a transação.
     * 
     * @return \JHV\Payment\CoreBundle\Operation\CreditInterface
     */
    function getCredit();
    
    /**
     * Definir pagamento.
     * Método associará um pagamento para esta associação.
     * 
     * @param \JHV\Payment\CoreBundle\Operation\DebitInterface $debit
     * @return self
     */
    function setDebit(DebitInterface $debit);
    
    /**
     * Localizar pagamento.
     * Verifica qual o pagamento associado a transação, retornando-o.
     * 
     * @return \JHV\Payment\CoreBundle\Operation\DebitInterface
     */
    function getDebit();
    
    /**
     * Localiza a operação relacionada.
     * Irá efetuar a busca da operação realizada para transação.
     * 
     * @return \JHV\Payment\CoreBundle\Operation\Model\OperationInterface
     */
    function getOperation();
    
}