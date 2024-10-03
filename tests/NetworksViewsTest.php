<?php

declare( strict_types =  1 );

namespace Tests;

use PHPUnit\Framework\Attributes\Depends;
use Ocolin\Wisdm\Wisdm;

class NetworksViewsTest extends TestBase
{
    public static int|string $network_id;

    public function testCreate() : int
    {
        $result = self::createNetworksView( network_id: self::$network_id );
        $this->globalTest( result: $result );
        $this->assertEquals( expected: 201, actual: $result->status );
        $this->assertEquals( expected: 'Created', actual: $result->status_message );
        $this->assertIsObject( actual: $result->body );

        return $result->body->id;
    }

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

    public function testGet() : void
    {
        $wisdm = new Wisdm();
        $result = $wisdm->call(
            path: '/networks/views',
        );
        print_r( $result );
        $this->globalTest( result: $result );
        $this->assertEquals( expected: 200, actual: $result->status );
        $this->assertEquals( expected: 'OK', actual: $result->status_message );
        $this->assertIsArray( actual: $result->body );
    }

    #[Depends('testCreate')]
    public function testDelete( int $id ) : void
    {
        $result = self::deleteNetworksViews( id: $id );
        $this->globalTest( result: $result );
        $this->assertEquals( expected: 200, actual: $result->status );
        $this->assertEquals( expected: 'OK', actual: $result->status_message );
        $this->assertIsObject( actual: $result->body );
    }

    public static function setUpBeforeClass(): void
    {
        self::$network_id = self::createNetwork()->body->id;
    }

    public static function tearDownAfterClass(): void
    {
        self::deleteNetwork( id: self::$network_id );
    }
}