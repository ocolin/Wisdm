<?php

declare( strict_types =  1 );

namespace Tests;

use PHPUnit\Framework\Attributes\Depends;
use Ocolin\Wisdm\Wisdm;

class AvailabilityTest extends TestBase
{
    /*
    public function testGet()
    {
        $wisdm = new Wisdm();
        $result = $wisdm->call( path: '/availability' );
        $this->globalTest( result: $result );
        $this->assertEquals( expected: 200, actual: $result->status );
        $this->assertEquals( expected: 'OK', actual: $result->status_message );
        $this->assertIsObject( actual: $result->body );
    }
    */

    public function testCheck()
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
        echo $result->status_message . "\n";
        $this->globalTest( result: $result );
        $this->assertEquals( expected: 200, actual: $result->status );
        $this->assertEquals( expected: 'OK', actual: $result->status_message );
        $this->assertIsArray( actual: $result->body );

        foreach( $result->body as $ap )
        {
            if( $ap->result == 'pass' ) {
                print_r( $ap );
                echo "--------------\n";
            }
        }
    }
}