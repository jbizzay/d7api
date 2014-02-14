<?php namespace D7api\Test\Plugin;

use D7api\Entity\Node;
use D7api\Entity\User;
use D7api\Plugin\Og;

class OgTest extends \PHPUnit_Framework_TestCase {

	protected static $test_type = 'd7api_group';

	protected static $node;
	protected static $user;

	public static function setUpBeforeClass() {
		static::$node = Node::create(static::$test_type);
		static::$node->title = 'mygroup';
		static::$node->save();
		static::$user = User::create('test@test.com');
	}

	public static function tearDownAfterClass() {
		static::$node->delete();
		static::$user->delete();
	}

	public function testGroupAddUserRemoveUser() {
		$group = new Og(static::$node->nid);
		$this->assertFalse($group->has_user(static::$user->uid));
		$group->add_user(static::$user->uid);
		$this->assertTrue($group->has_user(static::$user->uid));
		$group->remove_user(static::$user->uid);
		$this->assertFalse($group->has_user(static::$user->uid));
	}

	public function testGroupGetMembersList() {
		$group = new Og(static::$node->nid);
		$members = $group->get_users();
		// Will start out with the owner of the node
		$this->assertEquals(array(1 => 1), $members);
		$group->add_user(static::$user->uid);
		$members = $group->get_users();
		$this->assertNotEmpty($members[static::$user->uid]);
		$group->remove_user(static::$user->uid);
		$members = $group->get_users();
		$this->assertEmpty($members[static::$user->uid]);
	}

	public function testGetUsersGroups() {
		// Should start out not in any groups
		$groups = Og::get_user_groups(static::$user->uid);
		$this->assertEmpty($groups);
		// Add user to group
		$group = new Og(static::$node->nid);
		$group->add_user(static::$user->uid);
		// Get user's groups
		$groups = Og::get_user_groups(static::$user->uid);
		$this->assertNotEmpty($groups[static::$node->nid]);
		// Get user's groups by type (doesn't exist)
		$groups = Og::get_user_groups(static::$user->uid, 'nonexistant');
		$this->assertEmpty($groups);
		// Get user's groups by test type
		$groups = Og::get_user_groups(static::$user->uid, static::$test_type);
		$this->assertNotEmpty($groups[static::$node->nid]);
		// Reset
		$group->remove_user(static::$user->uid);
		$this->assertEmpty(Og::get_user_groups(static::$user->uid));
	}


}