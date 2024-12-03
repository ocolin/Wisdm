<?php

declare( strict_types =  1 );

namespace Tests;

use PHPUnit\Framework\Attributes\Depends;
use Ocolin\Wisdm\Wisdm;

class NetworksViewsTest extends TestBase
{
    public static int|string $network_id;


/* CREATE NETWORK VIEW
----------------------------------------------------------------------------- */

    /**
     * @return int ID of network view.
     */
    public function testCreate() : int
    {
        $result = self::createNetworksView( network_id: self::$network_id );
        $this->globalTest( result: $result );
        $this->assertEquals( expected: 201, actual: $result->status );
        $this->assertEquals( expected: 'Created', actual: $result->status_message );
        $this->assertIsObject( actual: $result->body );

        return $result->body->id;
    }



/* UPDATE NETWORK VIEW
----------------------------------------------------------------------------- */

    /**
     * @param int|string $id ID of network view.
     * @return void
     */
    #[Depends('testCreate')]
    public function testUpdate( int|string $id ) : void
    {
        $wisdm = new Wisdm();
        $result = $wisdm->call(
            path: '/networks/views/{id}',
            method: 'patch',
            params: [ 'id' => $id ],
            body: [ 'name' => 'PHPUnit_Update' ]
        );

        $this->globalTest( result: $result );
        $this->assertIsObject( actual: $result->body );
        $this->assertEquals( expected: 200, actual: $result->status );
        $this->assertEquals( expected: 'OK', actual: $result->status_message );
        $this->assertEquals( expected: 'PHPUnit_Update', actual: $result->body->name );
    }



/* GET NETWORK VIEW
----------------------------------------------------------------------------- */

    public function testGet() : void
    {
        $wisdm = new Wisdm();
        $result = $wisdm->call(
            path: '/networks/views',
        );
        $this->globalTest( result: $result );
        $this->assertEquals( expected: 200, actual: $result->status );
        $this->assertEquals( expected: 'OK', actual: $result->status_message );
        $this->assertIsArray( actual: $result->body );
    }



/* DELETE NETWORK VIEW
----------------------------------------------------------------------------- */

    /**
     * @param int $id ID of network view.
     * @return void
     */
    #[Depends('testCreate')]
    public function testDelete( int $id ) : void
    {
        $result = self::deleteNetworksViews( id: $id );
        $this->globalTest( result: $result );
        $this->assertEquals( expected: 200, actual: $result->status );
        $this->assertEquals( expected: 'OK', actual: $result->status_message );
        $this->assertIsObject( actual: $result->body );
    }



/* SETUP BEFORE CLASS
----------------------------------------------------------------------------- */

    public static function setUpBeforeClass(): void
    {
        self::$network_id = self::createNetwork()->body->id;
    }


/* TEAR DOWN AFTER CLASS
----------------------------------------------------------------------------- */

    public static function tearDownAfterClass(): void
    {
        self::deleteNetwork( id: self::$network_id );
    }
}