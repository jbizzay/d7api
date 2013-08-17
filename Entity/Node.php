<?php namespace D7api\Entity;
use D7api\Entity;
use Exception;

class Node extends Entity {

	public $node;

	protected function __construct($nid = null) {
		parent::__construct($nid);
		if ($nid) {
			$this->node = node_load($nid);
		}
	}

	public function __get($name) {
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
		return null;
	}

	public function __set($name, $value) {
		if (strpos($name, 'field_') !== false) {
			$this->node->{$name}[$this->node->language][0]['value'] = $value;
			return;
		}
		$this->node->$name = $value;
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
			throw new Exception("Invalid node type: ". $type);
		}
		$node = new \stdClass;
		$node->type = $type;
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

	public function save() {
		node_submit($this->node);
		node_save($this->node);
		return $this;
	}

}