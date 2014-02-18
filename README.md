d7api
=====

An api wrapper for Drupal 7 and doing various common tasks programmatically



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

Create a user with just an email address:

```php
$user = User::create('jbizzay@mail.net');
$user->save(); // If this user already exists, will throw exception

// Defaults that get set:
$user->name = 'jbizzay@mail.net'; // Email passed to ::create method
$user->mail = 'jbizzay@mail.net';
$user->pass = '';
$user->status = 1;
$user->init = 'jbizzay@mail.net';
$user->roles = array(DRUPAL_AUTHENTICATED_RID => 'authenticated user')
```

Load a user by uid, update username, email address

```php
$user = User::load(123); // Will throw an exception if user doesn't exist
$user->name = 'newname';
$user->mail = 'newmail@mail.net';
$user->save();
```

Get current loggedin user:

```php
$user = User::load();
echo 'Welcome, '. $user->name .'!';
```

Delete a user:

```php
$user = User::load(321);
$user->delete();
```

Organic Groups
-----

Create a new og node (content type must already be setup as a group) and load into Og plugin

```php
$node = Node::create('group-type');
$node->title = 'mygroup';
$node->save();
$group = new Og($node->nid);
```

Add user as member to the group:

```php
$group->add_user(123);
```

Check user is added:

```php
echo $group->has_user(123); // true
```

Remove user from group:

```php
$group->remove_user(123);
```

Get all the users in the group:

```php
$users = $group->get_users();
```

Get all the groups a user belongs to:

```php
$groups = Og::get_user_groups(321);
```