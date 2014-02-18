<?php namespace D7api\Entity;

use D7api\Entity;
//use EntityFieldQuery;

class Node extends Entity {

	public $node;

	protected static $_field_instances = array();

	protected function __construct($nid = null) {
		parent::__construct($nid);
		if ($nid) {
			$this->node = node_load($nid);
			if ( ! $this->node) {
				throw new \Exception("Invalid nid, couldn't load node");
			}
		}
	}

	public function __get($name) {
		// Call specific get_ method if exists
		if (method_exists($this, 'get_'. $name)) {
			return $this->{'get_'. $name}();
		}
		// Access cck property
		// Instead of having to use $node->field_name['und'][0]['value'],
		// this allows for a shortcut to simply use $node->field_name
		if (strpos($name, 'field_') !== false) {
			if (isset($this->node->{$name})) {
				return $this->node->{$name}[$this->node->language][0]['value'];
			}
		}
		// Access properties directly on the node object (title, uid, status, etc...)
		if (isset($this->node->$name)) {
			return $this->node->$name;
		}
		throw new \Exception("Can't get property: ". $name ." nid: ". $this->node->nid ." type: ". $this->node->type);
	}

	public function __set($name, $value) {
		// Call specific set_ method if exists
		if (method_exists($this, 'set_'. $name)) {
			$this->{'set_'. $name}($value);
			return;
		}
		if (strpos($name, 'field_') !== false) {
			$this->node->{$name}[$this->node->language][0]['value'] = $value;
			if (isset($this->node->{$name}[$this->node->language][0]['safe_value'])) {
				$this->node->{$name}[$this->node->language][0]['safe_value'] = $value;
			}
			return;
		}
		// @todo: for cck fields, check if the name is valid
		//if (isset($this->node->$name)) {
			$this->node->$name = $value;
			return;
		//}

		throw new \Exception("Can't set property: ". $name ." nid: ". $this->node->nid ." type: ". $this->node->type);
	}

	public function access($type = 'update') {
		return node_access($type, $this->node);
	}

	public static function count($type = null) {
		$query = db_select('node', 'n');
		$query->addExpression("COUNT(n.nid)");
		if ($type) {
			$query->condition('n.type', $type);
		}
		return $query->execute()->fetchField();
	}

	/**
	 * Node creation factory
	 * Sets defaults, and returns Entity\Node instance
	 * Does not save the node to the database
	 * @param string $type
	 *   Node type
	 * @return object Node
	 */
	public static function create($type = null) {
		// Make sure this type exists
		$types = static::get_types();
		if ( ! isset($types[$type])) {
			throw new \Exception("Invalid node type: ". $type);
		}
		$node = new \stdClass;
		$node->type = $type;
		node_object_prepare($node);
		// Set defaults
		$node->title = '';
		$node->language = LANGUAGE_NONE;
		$node->uid = 1;
		$node->name = 'admin';
		$node->log = 'Created with D7api';
		$node->status = 0;
		$node->comment = 0;
		$node->promote = 0;
		$node->sticky = 0;
		$node->path = array(
			'pathauto' => true,
		);
		$d_node = new static;
		$d_node->node = $node;
		return $d_node;
	}

	public function delete() {
		node_delete($this->node->nid);
		unset($this->node->nid, $this->node->vid, $this->node->created, $this->node->updated);
		return $this;
	}

	public static function get_types() {
		return node_type_get_types();
	}

	public function get_url() {
		return url('node/'. $this->node->nid, array('absolute' => true));
	}

	public static function load_by_field($field_name, $value) {
		$query = new \EntityFieldQuery();
		$query->entityCondition('entity_type', 'node')
			->fieldCondition($field_name, 'value', $value, '=')
			->range(0, 1)
			->addMetaData('account', user_load(1));
		$result = $query->execute();
		if (isset($result['node'])) {
			$nids = array_keys($result['node']);
			return static::load($nids[0]);
		}
	}

	public function save() {
		node_save($this->node);
		return $this;
	}

}