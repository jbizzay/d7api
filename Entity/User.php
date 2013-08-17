<?php namespace D7api\Entity;
use D7api\Entity;

class User extends Entity {

	public $user;

	protected function _load($uid) {
		$this->user = user_load($uid);
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

	public static function create($type = null) {

	}

	public static function get_roles() {
		$roles = user_roles(true);
		unset($roles[2]);
		return $roles;
	}

}