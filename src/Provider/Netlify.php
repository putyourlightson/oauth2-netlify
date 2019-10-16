<?php
/**
 * @copyright Copyright (c) PutYourLightsOn
 */

namespace Putyourlightson\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;

class Netlify extends AbstractProvider
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function getBaseAuthorizationUrl()
    {
        return 'http://app.netlify.com/authorize';
    }

    /**
     * @inheritdoc
     */
    public function getBaseAccessTokenUrl(array $params)
    {
        return 'https://api.netlify.com/oauth/token';
    }

    /**
     * @inheritdoc
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return 'https://api.netlify.com/api/v1/user';
    }

    // Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    protected function getDefaultScopes()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        if ($response->getStatusCode() != 200) {
            $message = $data['message'] ?? '';
            $code = $data['code'] ?? 0;
            throw new IdentityProviderException($message, $code, $data);
        }
    }

    /**
     * @inheritdoc
     */
    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new NetlifyResourceOwner($response, $this->responseResourceOwnerId);
    }
}
