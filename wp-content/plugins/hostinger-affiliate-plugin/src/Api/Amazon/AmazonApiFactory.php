<?php
namespace Hostinger\AffiliatePlugin\Api\Amazon;

use Hostinger\AffiliatePlugin\Api\Amazon\ProxyApi\ProxyApi;
use Hostinger\AffiliatePlugin\Containers\Container;

class AmazonApiFactory {
    protected Container $container;

    public function __construct( Container $container ) {
        $this->container = $container;
    }

    public function get_api_factory(): ProxyApi {
        return $this->container->get( ProxyApi::class );
    }
}
