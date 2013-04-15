<?php

namespace JHV\Payment\CoreBundle\Operation;

/**
 * DebitInterface
 * Classe no qual refere-se a operação de débito, ou seja, o pagamento.
 * 
 * Definição de status:
 * 1 . STATE_NEW            - Pagamento criado pela aplicação.
 * 2 . STATE_EXPIRED        - Pagamento expirado pela falta de transação ou captura.
 * 3 . STATE_AUTHORIZED     - Informações já fornecidas pelo cliente e autorizadas.
 * 4 . STATE_FAILED         - Informações fornecidas não autorizadas pela operadora ou alguma falha durante o processo.
 * 5 . STATE_RESERVED       - Pagamento está em progresso, reservado pelo sacador.
 * 6 . STATE_CANCELED       - Pagamento cancelado pelo sacado.
 * 7 . STATE_CHARGEBACK     - Foi efetuado uma requisição para requisição de devolução de fundos pelo sacado.
 * 8 . STATE_CAPTURED       - Pagamento capturado pelo sacador.
 * 9 . STATE_REFUNDED       - Fundos retornandos ao sacado.
 * 10. STATE_SETTLED        - Fundos depositados ao sacador. Novas requisições não serão bem vindas para operação.
 * 
 * @author Jorge Vahldick <jvahldick@gmail.com>
 * @license Please view /Resources/meta/LICENCE
 * @copyright (c) 2013
 */
interface DebitInterface
{
    
    const STATE_NEW         = 1;
    const STATE_EXPIRED     = 2;
    const STATE_AUTHORIZED  = 3;
    const STATE_FAILED      = 4;
    const STATE_RESERVED    = 5;
    const STATE_CANCELED    = 6;
    const STATE_CAPTURED    = 7;
    const STATE_CHARGEBACK  = 8;
    const STATE_REFUNDED    = 9;
    const STATE_SETTLED     = 10;
    
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
     * O pagamento está expirado?
     * Método para definir se o pagamento está expirado.
     * 
     * @param boolean $expired
     * @return self
     */
    function setExpired($boolean);
    
    /**
     * O pagamento está expirado?
     * Método para verificar se o pagamento se encontra expirado.
     * 
     * @return boolean
     */
    function isExpired();
    
    /**
     * Data de expiração.
     * Método para definir a data de expiração do pagamento.
     * 
     * @param \DateTime $datetime Data de expiração
     * @return self
     */
    function setExpirationDate(\DateTime $datetime);
    
    /**
     * Data de expiração.
     * Método para localizar qual a data de expiração do pagamento.
     * 
     * @return \DateTime
     */
    function getExpirationDate();
    
}