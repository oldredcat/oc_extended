<?php

namespace System\Engine;

/**
* Router class
*/
final class Router
{
	private Registry $registry;
	private array $pre_action = [];
	private $error;
	
	/**
	 * Constructor
	 *
	 * @param	object	$registry
 	*/
	public function __construct(Registry $registry)
    {
		$this->registry = $registry;
	}
	
	/**
	 * 
	 *
	 * @param	object	$pre_action
 	*/	
	public function addPreAction(Action $pre_action)
    {
		$this->pre_action[] = $pre_action;
	}

	/**
	 * 
	 *
	 * @param	object	$action
	 * @param	object	$error
 	*/		
	public function dispatch(Action $action, Action $error)
    {
		$this->error = $error;
		foreach ($this->pre_action as $pre_action) {
			$result = $this->execute($pre_action);
			if ($result instanceof Action) {
				$action = $result;
				break;
			}
		}
		while ($action instanceof Action) {
			$action = $this->execute($action);
		}
	}
	
	/**
	 * 
	 *
	 * @param	object	$action
	 * @return	object
 	*/
	private function execute(Action $action): Action|null
    {
		$result = $action->execute($this->registry);

		if ($result instanceof Action) return $result;

		if ($result instanceof \Exception) {
			$action = $this->error;
			$this->error = null;
			return $action;
		}

        return null;
	}
}