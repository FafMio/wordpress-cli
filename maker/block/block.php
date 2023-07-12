<?php

use Theme\CustomBlock\BlockTemplate;

include "BlockFill.php";

( new BlockTemplate( $context ) )->render( "partials/acf/blocks//-/referrer/-/.html.twig", function ( $context ) {

    return $context;
} );