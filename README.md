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
		
	]
]);
```
