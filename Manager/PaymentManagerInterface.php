<?php

namespace JHV\Payment\CoreBundle\Manager;

use JHV\Payment\CoreBundle\Instruction\PaymentInstructionInterface;
use JHV\Payment\CoreBundle\Operation\DebitInterface;

/**
 * PaymentManagerInterface
 * 
 * @author Jorge Vahldick <jvahldick@gmail.com>
 * @license Please view /Resources/meta/LICENCE
 * @copyright (c) 2013
 */
interface PaymentManagerInterface
{
    
    /**
     * Criar instrução de pagamento.
     * Caso a instrução de pagamento esteja marcado como status de "novo",
     * persistira o objeto junto ao banco de dados, caso contrário ocorrerá erro.
     * 
     * @throws \InvalidArgumentException
     * @param \JHV\Payment\CoreBundle\Instruction\PaymentInstructionInterface $instruction
     * @return void
     */
    function createPaymentInstruction(PaymentInstructionInterface $instruction);
    
    /**
     * Criação de um novo crédito | estorno.
     * Este método irá efetuar a criação do estorno ou crédito independente 
     * junto ao banco de dados com associação a uma instrução de pagamento.
     * 
     * Caso o terceiro parâmetro seja enviado significa que é um estorno, caso
     * contrário significa que é um crédito independente.
     * 
     * @param \JHV\Payment\CoreBundle\Instruction\PaymentInstructionInterface $instruction
     * @param type $amount
     * @param \JHV\Payment\CoreBundle\Operation\DebitInterface $debit
     */
    function createCredit(PaymentInstructionInterface $instruction, $amount, DebitInterface $debit = null);
    
    /**
     * Efetua a criação do pagamento.
     * Este método efetua a criação do pagamento junto ao banco de dados
     * já realizando a associação junto a instrução de pagamento.
     * 
     * @param \JHV\Payment\CoreBundle\Instruction\PaymentInstructionInterface $instruction
     * @param decimal $amount
     */
    function createDebit(PaymentInstructionInterface $instruction, $amount);
    
    /**
     * Localizar instrução de pagamento.
     * Efetuar a busca da instrução do pagamento através do ID.
     * 
     * @param integer $id
     * @return \JHV\Payment\CoreBundle\Instruction\PaymentInstructionInterface
     * @throws \JHV\Payment\CoreBundle\Exception\NotFoundException
     */
    function findInstruction($id);
    
    /**
     * Localizar meio de pagamento.
     * Este método utilizar-se do gerenciador de meios de pagamento,
     * procurando assim um meio de pagamento através de seu nome de referência.
     * 
     * @param string $name
     * @return \JHV\Payment\ServiceBundle\Model\PaymentMethodInterface
     */
    function findPaymentMethod($name);
    
    /**
     * Localizar plugin.
     * Método irá procurar o plugin associado através do nome de referência.
     * 
     * @param \JHV\Payment\ServiceBundle\Plugin\PluginInterface $name
     */
    function findPlugin($name);
    
    /**
     * Efetuará a autorização e captura.
     * Este método criará uma transação junto ao banco de dados, fazendo durante
     * seu processo a tentativa de executar a operação junto ao plugin.
     * 
     * Caso ocorra algum erro durante a operação ocorrerá um rollback, fazendo
     * com que não aconteça problemas em algum registro junto ao banco de dados.
     * 
     * @param \JHV\Payment\CoreBundle\Operation\DebitInterface $debit
     * @param decimal $amount
     * 
     * @return \JHV\Payment\CoreBundle\Operator\Connection\ResultInterface
     */
    function authorizeCapture(DebitInterface $debit, $amount);
    
    function authorize(DebitInterface $debit, $amount);
    
    function capture(DebitInterface $debit, $amount);
    
    function refund(DebitInterface $debit, $amount);
    
}