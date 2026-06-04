<?php

use NSL\Persistent\Persistent;
use NSL\PKCE\PKCE;

require_once NSL_PATH . '/includes/oauth2.php';
require_once NSL_PATH . '/NSL/PCKE/PKCE.php';


class NextendSocialProviderVKClient extends NextendSocialOauth2 {

    protected $access_token_data = array(
        'access_token' => '',
        'expires_in'   => -1,
        'created'      => -1
    );

    protected $endpointAuthorization = 'https://id.vk.com/authorize';
    protected $endpointAccessToken = 'https://id.vk.com/oauth2/auth';
    protected $endpointRestAPI = 'https://id.vk.com/oauth2';

    protected $scopes = array(
        'vkid.personal_info'
    );

    protected function extendHttpArgs($http_args) {
        $userAccessToken = $this->getUserAccessToken();
        if ($userAccessToken) {
            $http_args['body']['client_id']    = $this->client_id;
            $http_args['body']['access_token'] = $userAccessToken;
        }

        return $http_args;
    }

    protected function extendAllHttpArgs($http_args) {
        if ($this->client_id && $this->client_secret) {
            $http_args['headers'] = array(
                'Content-Type' => 'application/x-www-form-urlencoded',
            );
        }

        return $http_args;
    }

    public function createAuthUrl() {
        try {

            $codeVerifier = PKCE::generateCodeVerifier(128);

            $args = array(
                'response_type'         => 'code',
                'client_id'             => urlencode($this->client_id),
                'code_challenge'        => PKCE::generateCodeChallenge($codeVerifier),
                'code_challenge_method' => 'S256',
                'redirect_uri'          => urlencode($this->redirect_uri),
                'state'                 => urlencode($this->getState()),
            );
            Persistent::set($this->providerID . '_code_verifier', $codeVerifier);


            $scopes = apply_filters('nsl_' . $this->providerID . '_scopes', $this->scopes);
            if (count($scopes)) {
                $args['scope'] = rawurlencode($this->formatScopes($scopes));
            }

            $args = apply_filters('nsl_' . $this->providerID . '_auth_url_args', $args);

            return add_query_arg($args, $this->getEndpointAuthorization());
        } catch (Exception $e) {
            throw new NSLSanitizedRequestErrorMessageException($e->getMessage());
        }
    }


    protected function extendAuthenticateHttpArgs($http_args) {
        $http_args['body'] = [
            'grant_type'    => 'authorization_code',
            'code_verifier' => Persistent::get($this->providerID . '_code_verifier'),
            'redirect_uri'  => $this->redirect_uri,
            'code'          => $_GET['code'],
            'client_id'     => $this->client_id,
            'device_id'     => $_GET['device_id'],
            'state'         => urlencode($this->getState()),
        ];

        return $http_args;
    }

    public function getUserAccessToken() {
        if (isset($this->access_token_data['access_token']) && !empty($this->access_token_data['access_token'])) {
            return $this->access_token_data['access_token'];
        }

        return false;
    }
}