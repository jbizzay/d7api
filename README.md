d7api
=====

An OO wrapper for Drupal 7

Programmatic operations on things like nodes, users, taxonomies etc... can be cumbersome as you have to go back and forth between inspecting the object you are trying to import, and tweaking your code until it *just works* Ever try to programmatically add an image to a node's image field? This task shouldn't take a day to surf forums and play with your code until it finally works. Rather:

```php
$node = Node::load(123);
$file = File::upload('http://google.com/myimage.jpg');
$file->save();
$node->field_my_image = $file;
$node->save();
```

Node
-----

Creating a node:

```php
$node = Node::create('content-type');
$node->save();
echo $node->nid;
```

Sensible defaults are set for the node:

```php
$node->title = ''
$node->language : 'und'
$node->uid : '1'
$node->user : 'admin'
$node->log : 'Created by D7api'
$node->status : 0
$node->comment : 0
$node->promote : 0
$node->sticky : 0
```

Create a node, set a title, and a cck text field value, and set to published

```php
$node = Node::create('content-type');
$node->title = 'Test Node';
$node->status = 1;
$node->field_sub_title = "My sub-title";
$node->save();
echo $node->nid;
```

Loading a node, modifying a cck text field:

```php
$node = Node::load(123);
$node->field_message = 'Updated message!';
$node->save();
```

User
-----

Create a user:

```php
$user = User::create();
// Exception is thrown if required fields aren't set
$user->save(); // throws exception

$user = User::create();
$user->name = 'jbizzay';
$user->mail = 'jbizzay@mail.net';
$user->save();
```
