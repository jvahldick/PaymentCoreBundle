<?php

namespace JHV\Payment\CoreBundle\Operation\Model;

/**
 * OperationManagerInterface
 * 
 * @author Jorge Vahldick <jvahldick@gmail.com>
 * @license Please view /Resources/meta/LICENCE
 * @copyright (c) 2013
 */
interface OperationManagerInterface
{
    
    /**
     * Criar objeto.
     * Através da classe da operação definida, criará uma nova operação.
     * As operações podem ser: débito / crédito
     * 
     * @return \JHV\Payment\CoreBundle\Operation\Model\OperationInterface
     */
    function create();
    
    /**
     * Localizar uma operação.
     * Como parâmetro deve ser passado o id da operação, efetuando assim
     * a localização da operação através de seu identificador
     * 
     * @param integer $id
     * @return \JHV\Payment\CoreBundle\Operation\Model\OperationInterface|null
     */
    function find($id);
    
    /**
     * Localizar uma operação por critérios.
     * Através de um array de critérios poderá ser localizado "uma" operação.
     * 
     * @param array $criteria
     * @return \JHV\Payment\CoreBundle\Operation\Model\OperationInterface
     */
    function findOneBy(array $criteria);
    
    /**
     * Localização das operações.
     * Efetuará uma busca de todas as operações registradas.
     * 
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    function findAll();
    
    /**
     * Busca por critérios.
     * Efetuará uma busca das operações através de um parâmetro do
     * tipo array contendo chaves e o que deve ser buscado.
     * 
     * @param array $criteria
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    function findBy(array $criteria);
    
    /**
     * Remover operação.
     * Exclui a operação passada por parâmetro.
     * 
     * @param \JHV\Payment\CoreBundle\Operation\Model\OperationInterface $operation
     * @return void
     * 
     */
    function delete(OperationInterface $operation);
    
    /**
     * Persist objeto.
     * Efetua a persistência do objeto junto ao banco de dados,
     * caso preferir executar o flush junto a operação, manter o segundo
     * parâmetro definido como true.
     * 
     * @param \JHV\Payment\CoreBundle\Operation\Model\OperationInterface $operation
     * @param boolean $flush Efetuar flush após persistência?
     */
    function persist(OperationInterface $operation, $flush = true);
    
    /**
     * Flush.
     * Efetuar o flush do gerenciador da entidade.
     * 
     * @return void
     */
    function flush();
    
}