<?php namespace D7api\Entity;

use D7api\Entity;

class User extends Entity {

	public $user;

	public function __construct($uid = null) {
		parent::__construct($uid);
		if ($uid) {
			$this->user = user_load($uid);
			if ( ! $this->user) {
				throw new \Exception("Invalid uid, couldn't load user.");
			}
		}
	}

	public function __get($name) {
		if (method_exists($this, 'get_'. $name)) {
			return $this->{'get_'. $name}();
		}
		if (isset($this->user->$name)) {
			return $this->user->$name;
		}
	}

	public function __set($name, $value) {
		if (method_exists($this, 'set_'. $name)) {
			return $this->{'set_'. $name}($value);
		}
		$this->user->$name = $value;
		return;
	}

	public static function count($role = null) {
		$query = db_select('users', 'u');
		$query->addExpression("COUNT(u.uid)");
		if ($role) {
			$query->join('users_roles', 'ur', 'u.uid = ur.uid');
			$query->join('role', 'r', 'ur.rid = r.rid');
			$query->condition('r.name', $role);
		}
		return $query->execute()->fetchField();
	}

	public static function create($email) {
		$fields = array(
			'name' => $email,
			'mail' => $email,
			'pass' => $password,
			'status' => 1,
			'init' => $email,
			'roles' => array(
				DRUPAL_AUTHENTICATED_RID => 'authenticated user'
			)
		);
		$account = user_save('', $fields);
		return static::load($account->uid);
	}

	public static function current_get_roles() {
		$roles = user_roles(true);
		unset($roles[2]);
		return $roles;
	}

}