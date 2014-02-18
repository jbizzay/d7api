<?php namespace D7api\Test\Entity;

use D7api\Entity\User;

class UserTest extends \PHPUnit_Framework_TestCase {
	
	public function testUserCreateSetDefaults() {
	  // Create a new user, need at least an email
	  $user = User::create('jbizzay@mail.net');
	  
	  // Check passed email address
	  $this->assertEquals('jbizzay@mail.net', $user->mail);
	  $this->assertEquals('jbizzay@mail.net', $user->name);
	  // Check defaults are set correctly
	  $this->assertEquals(1, $user->status);
	  $this->assertArrayHasKey(DRUPAL_AUTHENTICATED_RID, $user->roles);
	  // User shouldn't be saved to db yet
	  $this->assertEmpty($user->uid);
	  // Save user
	  $user->save();
	  $this->assertNotEmpty($user->uid);
	  $uid = $user->uid;
	  $user->delete();

	  // User doesn't exist, should throw exception
	  try {
	  	$user = User::load($uid);
	  } catch (\Exception $e) {
	  	return;
	  }
	  $this->fail('An expected exception has not been raised.');
	}

}