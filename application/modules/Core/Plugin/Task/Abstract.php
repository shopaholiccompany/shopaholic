<?php

abstract class Core_Plugin_Task_Abstract
{
  protected $_task;

  public function __construct(Zend_Db_Table_Row_Abstract $task)
  {
    if( !($task->getTable() instanceof Core_Model_DbTable_Tasks) ) {
      throw new Core_Model_Exception('Task must belong to the Core_Model_DbTable_Tasks table');
    }
    $this->_task = $task;
  }
  
  public function getTask()
  {
    return $this->_task;
  }
  
  public function getProgress()
  {
    return null;
  }

  public function getTotal()
  {
    return null;
  }
  
  abstract public function execute();
}