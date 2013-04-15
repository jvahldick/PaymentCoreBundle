<?php

namespace JHV\Payment\CoreBundle\Entity;

use JHV\Payment\CoreBundle\Instruction\PaymentInstruction as BaseIntruction;

/**
 * PaymentInstruction
 * 
 * @author Jorge Vahldick <jvahldick@gmail.com>
 * @license Please view /Resources/meta/LICENCE
 * @copyright (c) 2013
 */
class PaymentInstruction extends BaseIntruction
{
    
    /**
     * Identificador do crédito
     * @var integer
     */
    protected $id;
    
    /**
     * Localizará o identificador do crédito
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
}