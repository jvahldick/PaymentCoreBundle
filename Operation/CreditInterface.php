<?php

namespace JHV\Payment\CoreBundle\Operation;

/**
 * CreditInterface
 * Classe no qual refere-se a operação de crédito, ou seja, estornos de pagamentos
 * ou bônus devido ao pagamento já haver sido efetuado.
 * 
 * Definição de status:
 * 1 . STATE_NEW            - Novo crédito criado com sucesso.
 * 2 . STATE_FAILED         - Falha durante o processo de estorno.
 * 3 . STATE_RESERVED       - Estorno está em progresso.
 * 4 . STATE_CANCELED       - Estorno foi cancelado.
 * 5 . STATE_SETTLED        - Estorno estabelecido, liquidado.
 * 
 * @author Jorge Vahldick <jvahldick@gmail.com>
 * @license Please view /Resources/meta/LICENCE
 * @copyright (c) 2013
 */
interface CreditInterface
{
    
    const STATE_NEW = 1;
    const STATE_FAILED = 2;
    const STATE_RESERVED = 3;
    const STATE_CANCELED = 4;
    const STATE_SETTLED = 5;
    
    /**
     * Derivação de algum pagamento.
     * Caso seja derivado de algum pagamento, através deste método
     * a associação poderá ser estabelecida.
     * 
     * @param \JHV\Payment\CoreBundle\Operation\DebitInterface $debit
     * @return self
     */
    function setDebit(DebitInterface $debit);
    
    /**
     * Pagamento devirado.
     * Localiza o pagamento no qual o estorno foi associado.
     * 
     * @return DebitInterface|null
     */
    function getDebit();
    
    /**
     * Crédito ao cliente independente.
     * Alias para verificar do crédito possuir um pagamento associado.
     * 
     * São raros os casos de um "estorno" independete, porém pode ocorrer
     * em casos no qual os pagamentos já foram concluídos e não aceitam
     * mais modificações e por algum motivo o sacador necessita ainda
     * estornar o valor ao cliente, neste caso poderá ser realizado um
     * crédito independente.
     * 
     * @return boolean
     */
    function isIndependent();
    
}