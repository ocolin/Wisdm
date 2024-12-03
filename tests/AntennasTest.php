<?php

declare( strict_types =  1 );

namespace Tests;

use Ocolin\Wisdm\Wisdm;

class AntennasTest extends TestBase
{

/* GET ALL ANTENNA
----------------------------------------------------------------------------- */

    public function testGetAll() : void
    {
        $wisdm = new Wisdm();
        $result = $wisdm->get( path: '/antennas' );
        $this->globalTest( result: $result );
        $this->assertEquals( expected: 200, actual: $result->status );
        $this->assertEquals( expected: 'OK', actual: $result->status_message );
        $this->assertIsArray( actual: $result->body );
    }



/* GET AN ANTENNA
----------------------------------------------------------------------------- */

    public function testGet() : void
    {
        $wisdm = new Wisdm();
        $result = $wisdm->get( path: '/antennas/{id}', params: [ 'id' => 1 ] );
        $this->globalTest( result: $result );
        $this->assertEquals( expected: 200, actual: $result->status );
        $this->assertEquals( expected: 'OK', actual: $result->status_message );
        $this->assertIsObject( actual: $result->body );
    }



/* GET FAVORITES
----------------------------------------------------------------------------- */

    public function testFavoritesAll() : void
    {
        $wisdm = new Wisdm();
        $result = $wisdm->get( path: '/antennas/favourites' );
        $this->globalTest( result: $result );
        $this->assertEquals( expected: 200, actual: $result->status );
        $this->assertEquals( expected: 'OK', actual: $result->status_message );
        $this->assertIsArray( actual: $result->body );
    }
}