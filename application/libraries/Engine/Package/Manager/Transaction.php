<?php

class Engine_Package_Manager_Transaction implements Countable, IteratorAggregate
{
  protected $_operations;

  protected $_packagesByGuid;

  protected $_packagesByKey;
  
  public function __construct(array $operations)
  {
    $indexByGuid = array();
    $indexByKey = array();
    $operationsByKey = array();
    foreach( $operations as $operation ) {
      if( !$operation instanceof Engine_Package_Manager_Operation_Abstract ) {
        throw new Engine_Package_Manager_Exception('Not an operation');
      }
      $operationsByKey[$operation->getKey()] = $operationsByKey;
      $indexByGuid[$operation->getPackageGuid()] = $operation->getKey();
      if( $operation->getSourcePackage() ) {
        $indexByKey[$operation->getSourcePackage()->getKey()] = $operation->getKey();
      }
      if( $operation->getResultantPackage() ) {
        $indexByKey[$operation->getResultantPackage()->getKey()] = $operation->getKey();
      }
    }

    $this->_operations = array_values($operations);
    $this->_packagesByGuid = $indexByGuid;
    $this->_packagesByKey = $indexByKey;
  }

  public function __get($key)
  {
    if( isset($this->_operations[$key]) ) {
      return $this->_operations[$key];
    } else if( isset($this->_packagesByKey[$key]) ) {
      return $this->_packagesByKey[$key];
    } else if( isset($this->_packagesByGuid[$key]) ) {
      return $this->_packagesByGuid[$key];
    } else {
      return null;
    }
  }

  public function getOperations()
  {
    return array_values($this->_operations);
  }



  // Interfaces

  public function count()
  {
    return count($this->_operations);
  }

  public function getIterator()
  {
    return new ArrayObject($this->_operations);
  }
}