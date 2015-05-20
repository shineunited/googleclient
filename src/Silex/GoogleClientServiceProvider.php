<?php

namespace ShineUnited\GoogleClient\Silex;

use Silex\Application;
use Silex\ServiceProviderInterface;


class GoogleClientServiceProvider implements ServiceProviderInterface {

	public function register(Application $app) {

		$app['gapi.services'] = $app->share(function() use ($app) {
			$path = __DIR__ . '/../Resources/services.json';

			return json_decode($path, true);
		});

		$app['gapi.scopes'] = $app->share(function() use ($app) {
			$path = __DIR__ . '/../Resources/scopes.json';

			return json_decode($path, true);
		});

		$app['gapi.config'] = $app->share(function() use ($app) {
			$options = $app['gapi.options'];

			$config = new \Google_Config();

			if(isset($options['application_name'])) {
				$config->setApplicationName($options['application_name']);
			}

			if(isset($options['client_id'])) {
				$config->setClientId($options['client_id']);
			}

			if(isset($options['client_secret'])) {
				$config->setClientSecret($options['client_secret']);
			}

			if(isset($options['redirect_uri'])) {
				$config->setRedirectUri($options['redirect_uri']);
			}

			if(isset($options['access_type'])) {
				$config->setAccessType($options['access_type']);
			}

			if(isset($options['approval_prompt'])) {
				$config->setApprovalPrompt($options['approval_prompt']);
			}

			if(isset($options['developer_key'])) {
				$config->setDeveloperKey($options['developer_key']);
			}

			return $config;
		});

		$app['gapi.client'] = $app->share(function() use ($app) {
			$options = $app['gapi.options'];

			$client = new \Google_Client($app['gapi.config']);

			foreach(array('scope', 'scopes') as $key) {
				if(isset($options[$key]) && $options[$key]) {
					$scopes = $options[$key];
					if(!is_array($scopes)) {
						$scopes = array($scopes);
					}

					foreach($scopes as $scope) {
						if(isset($app['gapi.scopes'][strtolower($scope)])) {
							$scope = $app['gapi.scopes'][strtolower($scope)];
						}

						$client->addScope($scope);
					}
				}
			}

			if(isset($options['refresh_token'])) {
				$client->refreshToken($options['refresh_token']);
			}

			return $client;
		});

		$app['gapi.access_token'] = function() use ($app) {
			$token = json_decode($app['gapi.client']->getAccessToken());
			if(is_object($token) && isset($token->access_token)) {
				return $token->access_token;
			} else {
				return null;
			}
		};

		foreach($app['gapi.services'] as $service => $classname) {
			$app['gapi.service.' . $service] = $app->share(function() use ($app, $classname) {
				$class = new \ReflectionClass($classname);
				return $class->newInstance($app['gapi.client']);
			});
		}
	}

	public function boot(Application $app) {

	}
}
