<?php

namespace DiviFramework\Hub;

/**
 * User Account Hook class.
 */
class UserAccount {
	protected $container;
	protected $tokenOptionsKey;
	protected $accountTransientKey;

	public function __construct($container, $tokenOptionsKey = 'df-token', $accountTransientKey = 'df-account-data') {
		$this->container = $container;
		$this->tokenOptionsKey = $tokenOptionsKey;
		$this->accountTransientKey = $accountTransientKey;
	}

	public function deleteAuthToken() {
		delete_option($this->tokenOptionsKey);
		$this->deleteAccountData();
	}

	public function deleteAccountData() {
		delete_transient($this->accountTransientKey);
	}

	//refresh the jwt token.
	public function refreshToken() {
		$url = $this->container['provider_base_url'] . '/wp-json/wordpress-extensions/v1/refresh-token?t=' . time();
		$response = wp_remote_get($url, array(
			'method' => 'GET',
			'blocking' => true,
			'headers' => array(
				'Authorization' => 'Bearer ' . get_option($this->tokenOptionsKey, ''),
			),
		));

		if (isset($response['response']) && isset($response['response']['code'])) {
			if ($response['response']['code'] == 200) {
				$json = json_decode($response['body'], true);
				update_option($this->tokenOptionsKey, $json['token'], 'yes');
				return;
			}

		}

		$this->deleteAuthToken();
		return;
	}

	public function authenticated() {
		$token = get_option($this->tokenOptionsKey);

		return $token ? true : false;
	}

	public function postAuthentication() {
		if (!empty($_POST)) {
			$url = $this->container['provider_base_url'] . '/wp-json/jwt-auth/v1/token';
			$response = wp_remote_post($url, array(
				'method' => 'POST',
				'blocking' => true,
				'body' => $_POST,
			));

			$return = array('status' => 'error', 'message' => 'Invalid username or password');

			if (isset($response['response']) && isset($response['response']['code'])) {
				if ($response['response']['code'] == 200) {
					$json = json_decode($response['body'], true);
					$this->deleteAuthToken();
					add_option($this->tokenOptionsKey, $json['token'], '', 'yes');
					$this->deleteAccountData();
					$this->container['admin_dashboard']->redirectBase();
				}
			}

			return $return;

		} else {
			return false;
		}

	}

	/**
	 *
	 */
	public function getAccountData() {
		// fetch users membership details and plugin details
		$accountData = get_transient($this->accountTransientKey);

		if ($accountData == false) {
			$accountData = $this->syncAccountData();
		}

		return $accountData;
	}

	public function syncAccountData() {
		$url = $this->container['provider_base_url'] . '/wp-json/wordpress-extensions/v1/account-data?t=' . time();
		$response = wp_remote_get($url, array(
			'method' => 'GET',
			'blocking' => true,
			'headers' => array(
				'Authorization' => 'Bearer ' . get_option($this->tokenOptionsKey, ''),
			),
		));

		if (isset($response['response']) && isset($response['response']['code'])) {
			if ($response['response']['code'] == 200) {
				$json = json_decode($response['body'], true);
				set_transient($this->accountTransientKey, $json, 25 * HOUR_IN_SECONDS);
				return $json;
			}

		}

		$this->deleteAuthToken();
		return array();
	}

	/**
	 * Get the extension status.
	 */
	public function extensionStatus($extension) {
		switch ($extension['type']) {
		case 'Plugin':
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
			$status = '';

			// plugin active check.
			$plugin_active = is_plugin_active($extension['plugin_path']);

			if ($plugin_active) {
				if ($this->container['admin']->can_upgrade_plugin($extension['slug'])) {
					return 'plugin-update-available';
				}
				return 'plugin-installed-activated';

			}

			// plugin not activated check.

			$plugin_file = WP_PLUGIN_DIR . '/' . $extension['plugin_path'];
			if (file_exists($plugin_file)) {
				return 'plugin-installed';
			}

			return 'plugin-absent';
			break;

		default:
			# code...
			break;
		}

		return '';

	}

	public function getDownloadUrl($slug) {
		$url = $this->container['provider_base_url'] . '/wp-json/wordpress-extensions/v1/updates/' . $slug . '?action=hub-dl&php=' . phpversion();

		$response = wp_remote_get($url, array(
			'method' => 'GET',
			'blocking' => true,
			'headers' => array(
				'Authorization' => 'Bearer ' . get_option($this->tokenOptionsKey, ''),
			),
		));

		if (isset($response['response']) && isset($response['response']['code'])) {
			if ($response['response']['code'] == 200) {
				$json = json_decode($response['body'], true);
				return $json['download_url'];
			}

		}

		return '';
	}

	public function getExtension($slug) {
		$data = $this->getAccountData();

		foreach ($data['extensions'] as $extension) {
			if ($extension['slug'] == $slug) {
				return $extension;
			}
		}

		return false;
	}

}