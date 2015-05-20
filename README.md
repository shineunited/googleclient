Shine United - Google Client
============================


Installation
------------

#### Install Composer
```bash
$ curl -sS https://getcomposer.org/installer | php
```

#### Require Google Client Library
```bash
$ composer require shineunited/googleclient
```


Usage
-----


#### Silex

To use the Google Client with Silex, register the service provider:
```php
use ShineUnited\GoogleClient\Silex\GoogleClientServiceProvider;

$app->register(new GoogleClientServiceProvider(), [
	'gapi.options' => [
		...
	]
]);
```

##### Options

**application_name** (string)

**client_id** (string)

**client_secret** (string)

**redirect_url** (string)

**access_type** (string)

**approval_prompt** (string)

**developer_key** (string)

**scope** (string)

**scopes** (array)
