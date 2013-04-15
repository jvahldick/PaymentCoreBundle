<?php

namespace JHV\Payment\CoreBundle\Operator;

use JHV\Payment\CoreBundle\Operation\DebitInterface;

/**
 * PaymentOperatorInterface
 * 
 * Interface do operator de pagamentos.
 * Este operator é quem realizará as operações junto ao Plugin e objetos.
 * 
 * @author Jorge Vahldick <jvahldick@gmail.com>
 * @license Please view /Resources/meta/LICENCE
 * @copyright (c) 2013
 */
interface PaymentOperatorInterface
{
    
    /**
     * Autorização e captura.
     * Tentará efetuar a transação de autorização e captura junto
     * ao plugin de pagamento da instrução de pagamento.
     * 
     * Implementação de autorização e captura:
     * 
     * 1. Verificará se a instrução de pagamento é nova, fazendo assim a 
     * criação de uma nova transação, pois não há transações pendentes.
     * 
     * 2. Caso esteja marcado como "em aberto", irá verificar se o pagamento
     * está com o estado de autorizado, para poder posteriormente efetuar a
     * captura da transação.
     * Se está como autorizado, significa que há transações já realizadas com
     * sucesso que possuem um ID de transação, para efetuar a captura desta
     * transação.
     * Desta forma localizará a última tentativa de transação realizada 
     * com sucesso, criando uma nova transação baseada com o transactionId.
     * 
     * 3. Caso o estado seja diferenciado dos anteriores, ocorrerá um erro.
     * 
     * Após a criação da transação, haverá a tentativa de realizar a transação
     * junto a operadora de serviços.
     * 
     * @param \JHV\Payment\CoreBundle\Operation\DebitInterface $debit
     * @param decimal $amount
     * 
     * @return \JHV\Payment\CoreBundle\Operator\Connection\ResultInterface
     */
    function doAuthorizeCapture(DebitInterface $debit, $amount);
    
    /**
     * Autorização para pagamento.
     * 
     * Verificará se os dados informados quanto ao pagamento e aos informados
     * pelo cliente estão válidos para futura captura da transação.
     * 
     * 1. Verificará o estado da instrução de pagamento.
     * Para continuar o processo, o estado da instrução de pagamento deverá
     * estar marcado como "em aberto" ou "novo".
     * 
     * Assim, obrigatoriamente o pagamento deverá estar como NOVO.
     * 
     * 2. Caso o estado da instrução ou do pagamento não estejam ok, ocorrerá
     * um erro.
     * 
     * @param \JHV\Payment\CoreBundle\Operation\DebitInterface $debit
     * @param decimal $amount
     */
    function doAuthorization(DebitInterface $debit, $amount);
    
    /**
     * Captura da transação.
     * Criará a captura da transação junto a operadora.
     * 
     * A instrução de pagamento deverá estar como estado de em aberto, pois
     * deverão haver pagamentos autorizados.
     * 
     * Para que este operação seja realizada, obrigatoriamente o pagamento
     * deverá estar com status de "Autorizado", pois para realização de
     * somente captura, o pagamento já haveria de estar autorizado.
     * 
     * @param \JHV\Payment\CoreBundle\Operation\DebitInterface $debit
     * @param decimal $amount
     */
    function doCapture(DebitInterface $debit, $amount);
    
    /**
     * Efetuar o cancelamento / estorno do pagamento.
     * 
     * @param \JHV\Payment\CoreBundle\Operation\DebitInterface $debit
     * @param decimal $amount
     */
    function doRefund(DebitInterface $debit, $amount);
    
}