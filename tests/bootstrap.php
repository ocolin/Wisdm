<?php

declare( strict_types = 1 );

require_once __DIR__ . "/../vendor/autoload.php";

use Ocolin\EasyEnv\Env AS EasyEnv;

try {
    EasyEnv::load( files: __DIR__ . "/../.env.example" );
}
catch ( Throwable $e ) {
    echo $e->getMessage();
    exit(1);
}