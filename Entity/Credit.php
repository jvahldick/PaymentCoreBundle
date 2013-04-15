<?php

namespace JHV\Payment\CoreBundle\Entity;

use JHV\Payment\CoreBundle\Operation\Credit as BaseCredit;

/**
 * Credit
 * 
 * @author Jorge Vahldick <jvahldick@gmail.com>
 * @license Please view /Resources/meta/LICENCE
 * @copyright (c) 2013
 */
class Credit extends BaseCredit
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