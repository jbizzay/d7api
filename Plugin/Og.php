<?php namespace D7api\Plugin;

use D7api\Entity\Node;

class Og {
	
	public $node;

	public function __construct($nid) {
		$this->node = Node::load($nid);
	}

	/**
	 * Add a user to this organic group
	 * 
	 * @param integer $uid
	 */
	public function add_user($uid) {
		og_group('node', $this->node->nid, array(
			'entity' => $uid
		));
	}

	/**
	 * Get all members of this organic group
	 * 
	 * @param  integer $nid
	 * @return array   $users
	 */
	public function get_users() {
		$query = db_select('users', 'u');
	  $query
	    ->condition('u.uid', 0, '<>')
	    ->condition('u.status', 1, '=')
	    ->fields('u', array('uid'))
	    ->join('og_membership', 'ogm', "ogm.gid = :gid AND u.uid = ogm.etid AND ogm.entity_type = 'user'", array(
	    	':gid' => $this->node->nid
	    ));
	  $results = $query->execute();
	  $members = array();
	  foreach ($results as $result) {
	  	$members[$result->uid] = $result->uid;
	  }
	  return $members;
	}

	/**
	 * Determine if this group has a user as member
	 * 
	 * @param  integer  $uid
	 * @return boolean user belongs to group and is not pending or blocked
	 */
	public function has_user($uid) {
		return og_is_member('node', $this->node->nid, 'user', user_load($uid));
	}

	/**
	 * Remove a user from this group
	 * 
	 * @param  integer $uid
	 * @return void
	 */
	public function remove_user($uid) {
		og_ungroup('node', $this->node->nid, 'user', $uid);
	}

}