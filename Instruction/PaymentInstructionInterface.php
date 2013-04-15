<?php

namespace JHV\Payment\CoreBundle\Instruction;

use JHV\Payment\CoreBundle\Operation\DebitInterface;
use JHV\Payment\CoreBundle\Operation\CreditInterface;

/**
 * PaymentInstructionInterface
 * 
 * @author Jorge Vahldick <jvahldick@gmail.com>
 * @license Please view /Resources/meta/LICENCE
 * @copyright (c) 2013
 */
interface PaymentInstructionInterface
{
    
    const STATE_NEW     = 1;
    const STATE_OPENED  = 2;
    const STATE_CLOSED  = 3;
    const STATE_INVALID = 4;
    
    /**
     * Valor do pagamento.
     * Definir qual é o valor instruído para o pagamento.
     * 
     * @param decimal $amount
     * @return self
     */
    function setAmount($amount);
    
    /**
     * Valor da instrução de pagamento.
     * Verificar qual o valor que foi definido para o pagamento.
     * 
     * @return decimal
     */
    function getAmount();
    
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
     * Valor estornado.
     * Define qual o valor estornado do pagamento.
     * 
     * @param decimal $amount
     * @return self
     */
    function setRefundedAmount($amount);
    
    /**
     * Valor estornado.
     * Verifica qual foi o valor estornado da operação.
     * 
     * @return decimal
     */
    function getRefundedAmount();
    
    /**
     * Definirá qual a moeda do pagamento
     * A moeda deverá ser definida com sua sigla, de acordo
     * com as regras da ISO-4217
     * 
     * @link http://www.iso.org/iso/home/standards/currency_codes.htm Descrição da ISO
     * @link http://www.xe.com/iso4217.php Siglas em conformidade com seus países
     * 
     * @param string $currency
     * @return self
     */
    function setCurrency($currency);
    
    /**
     * Localizará a sigla da moeda escolhida para instrução de pagamento
     * Sigla esta armazenada de acordo com ISO-4217
     * 
     * @return string
     */
    function getCurrency();
    
    /**
     * Define o nome do "plugin" utilizado para transação
     * Serviço: nome do gateway definido em ServiceBundle
     * 
     * @param string $service
     * @return self
     */
    function setServiceName($service);
    
    /**
     * Nome do plugin.
     * Localização do nome do serviço/plugin que foi definido para 
     * utilização junto a instrução de pagamento.
     * 
     * @return string
     */
    function getServiceName();
    
    /**
     * Forma de pagamento.
     * Definição do nome da forma de pagamento escolhida.
     * 
     * @param string $paymentMethod
     * @return self
     */
    function setPaymentMethod($paymentMethod);
    
    /**
     * Forma de pagamento.
     * Localização do nome identificado da forma de pagamento.
     * 
     * @return string
     */
    function getPaymentMethod();
    
    /**
     * Definirá o estado da instrução de pagamento
     * Estados disponíveis:
     * 1: STATE_NEW     - Nova instrução de pagamento
     * 2: STATE_OPENED  - Instrução em aberto
     * 3: STATE_CLOSED  - Instrução já finalizada
     * 4: STATE_INVALID - Instrução inválida
     * 
     * @param integer $state
     * @return self
     */
    function setState($state);
    
    /**
     * Localizará em qual estado a instrução de pagamento se encontra
     * 
     * 1: STATE_NEW     - Instrução de pagamento nova
     * Esta significa que a instrução de pagamento ainda é nova e não tem nenhuma
     * execução quanto a operações.
     * 
     * 2: STATE_OPENED  - Instrução em aberto
     * Este estado significa que ainda há operações internas em aberto
     * 
     * 3: STATE_CLOSED  - Instrução já finalizada
     * Não há transações ou operações em aberto
     * 
     * 4: STATE_INVALID - Instrução inválida
     * Significa que houve erros em relação a "última tentativa" de executar
     * uma operação junto ao serviço definido
     * 
     * @return integer
     */
    function getState();
    
    /**
     * Incluir débito.
     * Adicionará um novo pagamento para ser associado com a instrução.
     * 
     * @param \JHV\Payment\CoreBundle\Operation\DebitInterface $operation
     * @return self
     */
    function addDebitOperation(DebitInterface $operation);
    
    /**
     * Incluir "estorno / crédito".
     * Adicionará um novo crédito associado com a instrução de pagamento.
     * 
     * @param \JHV\Payment\CoreBundle\Operation\CreditInterface $operation
     * @return self
     */
    function addCreditOperation(CreditInterface $operation);
    
    /**
     * Operações de débito.
     * Localizará todas as operações de débito associadas a instrução.
     * 
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    function getDebitOperations();
    
    /**
     * Operações de crédito.
     * Localizará todas as operações de crédito associadas a instrução.
     * 
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    function getCreditOperations();
    
    /**
     * Dados extras da instrução.
     * Array com informações adicionais quanto ao pagamento.
     * 
     * @param array $data
     * @return self
     */
    function setExtendedData(array $data);
    
    /**
     * Dados extendidos.
     * Localizará dados diferenciados referentes a instrução de pagamento.
     * 
     * @return array
     */
    function getExtendedData();
    
    /**
     * Verificar qual o balanço para instrução de pagamento.
     * Este método deverá percorrer todas as operações realizadas para
     * retornar ao usuário o saldo referente a instrução de pagamento.
     * 
     * @return decimal
     */
    function getBalance();
    
    /** @todo Descrever */
    function hasPendingTransaction();
    
    /** @todo Descrever */
    function getPendingTransaction();
    
}