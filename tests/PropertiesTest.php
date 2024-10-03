<?php

declare( strict_types =  1 );

namespace Tests;

use PHPUnit\Framework\Attributes\Depends;
use Ocolin\Wisdm\Wisdm;

class PropertiesTest extends TestBase
{

    /*
    public function testCreate() : void
    {
        $wisdm = new Wisdm();
        $result = $wisdm->call(
            path: '/properties/property-sets',
            method: 'post',
            body: []
        );
        $this->globalTest( result: $result );
        print_r( $result );
    }
    */

    /*
    public function testPostcodeLookup() : void
    {
        $wisdm = new Wisdm();
        $result = $wisdm->call(
            path: '/properties/property-sets/{id}/postcode-lookup',
            params: [
                'id' => 1692, 'postcode' => 95060
            ]
        );
        $this->globalTest( result: $result );
        $this->assertEquals( expected: 200, actual: $result->status );
        $this->assertEquals( expected: 'OK', actual: $result->status_message );
        $this->assertIsArray( actual: $result->body );
    }
    */

    /*
    public function testApCoverageReport() : void
    {
        $wisdm = new Wisdm();
        $result = $wisdm->call(
            path: '/properties/property-sets/{id}/ap-coverage-report.csv',
            params: [
                'id' => 1692,
                'latitude' => 36.983972,
                'longitude' => -121.977748,
                'antenna_id' => 122,
                'eirp' => 36,
                'frequency' => 5700,
                'height' => 1.83,
                'los_model_id' => 324
            ]
        );
        $this->globalTest( result: $result );
        $this->assertEquals( expected: 200, actual: $result->status );
        $this->assertEquals( expected: 'OK', actual: $result->status_message );
        $this->assertIsString( actual: $result->body );
    }
    */

    /*
    public function testGet()
    {
        $wisdm = new Wisdm();
        $result = $wisdm->call(
            path: '/properties/property-sets/{id}',
            params: ['id' => 1692]
        );
        $this->globalTest(result: $result);
        $this->assertEquals(expected: 200, actual: $result->status);
        $this->assertEquals(expected: 'OK', actual: $result->status_message);
        $this->assertIsObject(actual: $result->body);
    }
    */

    /*
    public function testGetAll()
    {
        $wisdm = new Wisdm();
        $result = $wisdm->call(
            path: '/properties/property-sets',
        );
        $this->globalTest(result: $result);
        $this->assertEquals(expected: 200, actual: $result->status);
        $this->assertEquals(expected: 'OK', actual: $result->status_message);
        $this->assertIsArray(actual: $result->body );
        print_r( $result );
    }
    */
}