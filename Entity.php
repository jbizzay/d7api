<?php namespace D7api;

abstract class Entity {

	public $entity_id;

	protected static $_instances = array();

	protected function __construct($entity_id = null) {
		if ($entity_id) {
			$this->entity_id = $entity_id;
		}
	}

	public static function create($type = null) {
		
	}

	public static function load($entity_id) {
		if ( ! isset(static::$_instances[$entity_id])) {
			static::$_instances[$entity_id] = new static($entity_id);
		}
		return static::$_instances[$entity_id];
	}


}