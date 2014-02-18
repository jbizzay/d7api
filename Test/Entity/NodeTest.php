<?php namespace D7api\Test\Entity\Node;
use D7api\Entity\Node;

class NodeTest extends \PHPUnit_Framework_TestCase {

	protected $test_type = 'd7api_test';
	
	public function testNodeCreateSetDefaults() {
		// Create a new node
		$node = Node::create($this->test_type);
		// Should set sensible defaults
		$this->assertEquals($this->test_type, $node->type);
		$this->assertEquals('', $node->title);
		$this->assertEquals(LANGUAGE_NONE, $node->language);
		$this->assertEquals(1, $node->uid);
		$this->assertEquals('admin', $node->name);
		$this->assertContains('D7api', $node->log);
		$this->assertEquals(0, $node->status);
		$this->assertEquals(0, $node->comment);
		$this->assertEquals(0, $node->promote);
		$this->assertEquals(0, $node->sticky);
	}

	public function testNodeCreateSetProperties() {
		// Create a node and set properties
		$node = Node::create($this->test_type);
		$node->title = 'Testing';
		$node->log = 'Test log';
		$node->status = 1;
		$node->comment = 2;
		$node->promote = 0;
		$node->sticky = 1;
		$this->assertEquals($this->test_type, $node->type);
		$this->assertEquals('Testing', $node->title);
		$this->assertEquals(1, $node->status);
		$this->assertEquals(2, $node->comment);
		$this->assertEquals(0, $node->promote);
		$this->assertEquals(1, $node->sticky);
	}

	public function testNodeCreateNonExistantTypeThrowsException() {
		try {
			$node = Node::create('unknown');
		} catch (\Exception $e) {
			return;
		}
		$this->fail('An expected exception has not been raised.');
	}

	public function testNodeCRUD() {
		// Create a node
		$node = Node::create($this->test_type);
		$node->title = 'Test node';
		// Save and add to the database
		$node->save();
		$this->assertGreaterThan(0, $node->nid);
		// Reload this node
		$nid = $node->nid;
		$node = Node::load($nid);
		$this->assertEquals('admin', $node->name);
		$this->assertEquals(1, $node->uid);
		$node->title = 'Edit title';
		$node->status = 1;
		$node->save();
		// Reload again, should have updated title and status
		$node = Node::load($nid);
		$this->assertEquals($nid, $node->nid);
		$this->assertEquals('Edit title', $node->title);
		$this->assertEquals(1, $node->status);
		// Delete this node
		$node->delete();
		// Node data should stay, but certain properties should go away
		$this->assertFalse(isset($node->nid));
		$this->assertFalse(isset($node->vid));
		$this->assertFalse(isset($node->created));
		$this->assertFalse(isset($node->updated));
	}

	public function testNodeSetFieldsDirectly() {
		$node = Node::create($this->test_type);
		$node->title = 'Test node';
		$node->node->field_d7api_boolean_single['und'][0]['value'] = 1;
		$node->save();
		$this->assertEquals(1, $node->field_d7api_boolean_single);
		$node->delete();
	}

	public function testNodeLoadByField() {
		$node = Node::create($this->test_type);
		$node->title = 'Test node';
		$string = time();
		$node->field_d7api_text_single = $string;
		$node->save();
		$nid = $node->nid;
		$node = Node::load_by_field('field_d7api_text_single', $string);
		$this->assertEquals($nid, $node->nid);
		$node->delete();
	}

	public function testCckBoolean() {
		$node = Node::create($this->test_type);
		$node->field_d7api_boolean_single = 1;
		$node->save();
		$nid = $node->nid;
		$node = Node::load($nid);
		$this->assertEquals(1, $node->field_d7api_boolean_single);
		$node->delete();
	}

	public function testCckText() {
		$node = Node::create($this->test_type);
		$node->field_d7api_text_single = 'testing';
		$node->save();
		$nid = $node->nid;
		$node = Node::load($nid);
		$this->assertEquals('testing', $node->field_d7api_text_single);
		$node->delete();
	}

	public function testCckTextMultiple() {
		$node = Node::create($this->test_type);
		$node->field_d7api_text_single = 'testing';
		$node->save();
		$nid = $node->nid;
		$node = Node::load($nid);
		$this->assertEquals('testing', $node->field_d7api_text_single);
		$node->delete();
	}

	public function testCreateFullTestNode() {
		// Create a node utilizing all test cck fields
		$node = Node::create($this->test_type);
	}

}