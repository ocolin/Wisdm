<?php

declare( strict_types =  1 );

namespace Tests;

use PHPUnit\Framework\Attributes\Depends;
use Ocolin\Wisdm\Wisdm;

class AvailabilityTest extends TestBase
{

/* GET AVAILABILITY
----------------------------------------------------------------------------- */

    public function testGet() : void
    {
        $wisdm = new Wisdm();
        $result = $wisdm->call( path: '/availability' );
        $this->globalTest( result: $result );
        $this->assertEquals( expected: 200, actual: $result->status );
        $this->assertEquals( expected: 'OK', actual: $result->status_message );
        $this->assertIsArray( actual: $result->body );
    }


    /*
    public function testCheckerMap()
    {
        // THIS TEST CAN TAKE 4 MINUTES TO RUN.
        $wisdm = new Wisdm();
        $result = $wisdm->call(
            path: '/availability/{id}/availability-checker-map',
            params: [
                'id' => 92,
                'latitude' => 36.983972,
                'longitude' => -121.977748,
            ]
        );
        $this->globalTest( result: $result );
        $this->assertEquals( expected: 200, actual: $result->status );
        $this->assertEquals( expected: 'OK', actual: $result->status_message );
        $this->assertIsArray( actual: $result->body );
        print_r( $result );
    }
    */


/* GET POST CODE
----------------------------------------------------------------------------- */

    public function testPostcode() : void
    {
        $wisdm = new Wisdm();
        $result = $wisdm->call(
            path: '/availability/{id}/postcode-lookup',
            params: [
                'id' => 93,
                'postcode' => '95060'
            ]
        );
        $this->globalTest( result: $result );
        $this->assertEquals( expected: 200, actual: $result->status );
        $this->assertEquals( expected: 'OK', actual: $result->status_message );
        $this->assertIsArray( actual: $result->body );
    }



/* GET AVAILABILITY CHECK
----------------------------------------------------------------------------- */

    public function testAvailabilityCheck() : void
    {
        $wisdm = new Wisdm();
        $result = $wisdm->call(
            path: '/availability/{id}/check',
            params: [
                'id' => 93,
                'latitude' => 36.983972,
                'longitude' => -121.977748,
            ]
        );
        $this->globalTest( result: $result );
    }



/* CHECK AVAILABILITY
----------------------------------------------------------------------------- */

    public function testCheck() : void
    {
        $wisdm = new Wisdm();
        $result = $wisdm->call(
            path: '/availability/check',
            params: [
                'latitude' => '36.983972',
                'longitude' => '-121.977748',
                'property_set_id' => 1679,
                'los_model_id' => 324,
                'network_view_id' => 1303
            ]
        );
        $this->globalTest( result: $result );
        $this->assertEquals( expected: 200, actual: $result->status );
        $this->assertEquals( expected: 'OK', actual: $result->status_message );
        $this->assertIsArray( actual: $result->body );
    }
}