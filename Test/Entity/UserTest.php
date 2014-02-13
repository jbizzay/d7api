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
	  $this->assertNotEmpty($user->uid);
	}

}