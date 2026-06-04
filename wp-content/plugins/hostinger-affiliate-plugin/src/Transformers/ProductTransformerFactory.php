<?php
namespace Hostinger\AffiliatePlugin\Transformers;

use Hostinger\AffiliatePlugin\Containers\Container;

class ProductTransformerFactory {
    protected Container $container;

    public function __construct( Container $container ) {
        $this->container = $container;
    }

    public function get_transformer( string $marketplace = 'amazon' ): ProductTransformer {
        if ( $marketplace === 'mercado' ) {
            return $this->container->get( MercadoItemTransformer::class );
        }

        return $this->container->get( ProxyItemTransformer::class );
    }
}
