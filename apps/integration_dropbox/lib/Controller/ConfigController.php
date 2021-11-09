<?php
/**
 * Nextcloud - dropbox
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Julien Veyssier <eneiluj@posteo.net>
 * @copyright Julien Veyssier 2020
 */

namespace OCA\Dropbox\Controller;

use OCP\IConfig;
use OCP\IL10N;

use OCP\IRequest;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Controller;

use OCA\Dropbox\Service\DropboxAPIService;
use OCA\Dropbox\AppInfo\Application;

class ConfigController extends Controller {

	/**
	 * @var IConfig
	 */
	private $config;
	/**
	 * @var IL10N
	 */
	private $l;
	/**
	 * @var DropboxAPIService
	 */
	private $dropboxAPIService;
	/**
	 * @var string|null
	 */
	private $userId;

	public function __construct($appName,
								IRequest $request,
								IConfig $config,
								IL10N $l,
								DropboxAPIService $dropboxAPIService,
								?string $userId) {
        parent::__construct($appName, $request);
		$this->appName = $appName;
		$this->request = $request;
		$this->config = $config;
		$this->l = $l;
		$this->dropboxAPIService = $dropboxAPIService;
		$this->userId = $userId;
	}

    /**
     * set config values
     * @NoAdminRequired
     *
     * @param array $values
     * @return DataResponse
     */
    public function setConfig(array $values): DataResponse {
        foreach ($values as $key => $value) {
            $this->config->setUserValue($this->userId, Application::APP_ID, $key, $value);
        }
        if (isset($values['user_name']) && $values['user_name'] === '') {
            //// revoke token
            //$clientID = $this->config->getAppValue(Application::APP_ID, 'client_id', Application::DEFAULT_DROPBOX_CLIENT_ID);
            //$clientID = $clientID ?: Application::DEFAULT_DROPBOX_CLIENT_ID;
            //$clientSecret = $this->config->getAppValue(Application::APP_ID, 'client_secret', Application::DEFAULT_DROPBOX_CLIENT_SECRET);
            //$clientSecret = $clientSecret ?: Application::DEFAULT_DROPBOX_CLIENT_SECRET;
            //$accessToken = $this->config->getUserValue($this->userId, Application::APP_ID, 'token', '');
            //$refreshToken = $this->config->getUserValue($this->userId, Application::APP_ID, 'refresh_token', '');

            //$info = $this->dropboxAPIService->request(
            //    $accessToken, $refreshToken, $clientID, $clientSecret, 'auth/token/revoke', [], 'POST'
            //);

            $this->config->deleteUserValue($this->userId, Application::APP_ID, 'user_name');
            $this->config->deleteUserValue($this->userId, Application::APP_ID, 'uid');
            $this->config->deleteUserValue($this->userId, Application::APP_ID, 'account_id');
            $this->config->deleteUserValue($this->userId, Application::APP_ID, 'token');
            $this->config->deleteUserValue($this->userId, Application::APP_ID, 'refresh_token');
        }
        return new DataResponse(1);
    }

    /**
     * set admin config values
     *
     * @param array $values
     * @return DataResponse
     */
    public function setAdminConfig(array $values): DataResponse {
        foreach ($values as $key => $value) {
            $this->config->setAppValue(Application::APP_ID, $key, $value);
        }
        return new DataResponse(1);
    }

    /**
     * @NoAdminRequired
     *
     * @param string $code
     * @return DataResponse
     */
    public function submitAccessCode(string $code = ''): DataResponse {
        if ($code === '') {
            $message = $this->l->t('Invalid access code');
            return new DataResponse($message, 400);
        }
        $clientID = $this->config->getAppValue(Application::APP_ID, 'client_id', Application::DEFAULT_DROPBOX_CLIENT_ID);
        $clientID = $clientID ?: Application::DEFAULT_DROPBOX_CLIENT_ID;
        $clientSecret = $this->config->getAppValue(Application::APP_ID, 'client_secret', Application::DEFAULT_DROPBOX_CLIENT_SECRET);
        $clientSecret = $clientSecret ?: Application::DEFAULT_DROPBOX_CLIENT_SECRET;

        if ($clientID) {
            $result = $this->dropboxAPIService->requestOAuthAccessToken($clientID, $clientSecret, [
                'grant_type' => 'authorization_code',
                'code' => $code,
            ], 'POST');
            if (isset($result['access_token'], $result['refresh_token'])) {
                $accessToken = $result['access_token'];
                $this->config->setUserValue($this->userId, Application::APP_ID, 'token', $accessToken);
                $refreshToken = $result['refresh_token'];
                $this->config->setUserValue($this->userId, Application::APP_ID, 'refresh_token', $refreshToken);
                if (isset($result['uid'], $result['account_id'])) {
                    $this->config->setUserValue($this->userId, Application::APP_ID, 'uid', $result['uid']);
                    $this->config->setUserValue($this->userId, Application::APP_ID, 'account_id', $result['account_id']);
                }
                // get user information
                $data = [];
                $info = $this->dropboxAPIService->request(
                    $accessToken, $refreshToken, $clientID, $clientSecret, $this->userId, 'users/get_current_account', [], 'POST'
                );
                if (isset($info['name'], $info['name']['display_name'])) {
                    $this->config->setUserValue($this->userId, Application::APP_ID, 'user_name', $info['name']['display_name']);
                    $data['user_name'] = $info['name']['display_name'];
                } else {
                    $this->config->setUserValue($this->userId, Application::APP_ID, 'user_name', '??');
                    $data['user_name'] = '??';
                }
                return new DataResponse($data);
            } else {
                $message = $result['error_description'] ?? $result['error'] ?? 'missing token or refresh token';
            }
        } else {
            $message = $this->l->t('Error during OAuth exchanges');
        }
        return new DataResponse($message, 400);
    }
}
