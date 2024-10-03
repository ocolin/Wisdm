<?php

declare( strict_types =  1 );

namespace Tests;

use PHPUnit\Framework\TestCase;
use Ocolin\Wisdm\Wisdm;

class TestBase extends TestCase
{

    public static function createNetwork() : object
    {
        $widsm = new Wisdm();

        return $widsm->call(
            path: '/networks',
            method: 'post',
            body: [
                'name' => 'PHPUnit_Test',
                'colour' => '#808080'
            ]
        );
    }



    public static function deleteNetwork( int|string $id ) : object
    {
        $widsm = new Wisdm();

        return $widsm->call(
            path: '/networks/{id}',
            method: 'delete',
            params: [ 'id' => $id ]
        );
    }



    public static function createNetworksView( int|string $network_id ) : object
    {
        $widsm = new Wisdm();
        return $widsm->call(
            path: '/networks/views',
            method: 'post',
            body: [
                'name' => 'PHPUnit_Test',
                'networks' => [ $network_id ]
            ]
        );
    }



    public static function deleteNetworksViews( int|string $id ) : object
    {
        $widsm = new Wisdm();

        return $widsm->call(
            path: '/networks/views/{id}',
            method: 'delete',
            params: [ 'id' => $id ]
        );
    }

    public static function createLosModel() : object
    {
        $widsm = new Wisdm();
        return $widsm->call(
              path: '/los-models',
            method: 'post',
              body: [
                'name' => 'PHPUnit_Test',
                'backhaul_eirp' => 36,
                'backhaul_gain' => 23,
                'backhaul_min_rsl' => -75,
                'client_gain' => 20,
                'client_height' => 1.83,
                'elevation_model_id' => 103,
                'fresnel_min_clearage' => 1.0,
                'max_range' => 14484,
                'min_rsl' => -84
            ]
        );
    }

    public static function deleteLosModels( int|string $id ) : object
    {
        $widsm = new Wisdm();
        return $widsm->call(
              path: '/los-models/{id}',
            method: 'delete',
            params: [ 'id' => $id ]
        );
    }

    public function globalTest( mixed $result ) : void
    {
        $this->assertIsObject( $result );
        $this->assertObjectHasProperty( propertyName: 'status', object: $result );
        $this->assertObjectHasProperty( propertyName: 'status_message', object: $result );
        $this->assertObjectHasProperty( propertyName: 'body', object: $result );
        $this->assertIsInt( actual: $result->status );
        $this->assertIsString( actual: $result->status_message );
    }
}