<?php

declare( strict_types =  1 );

namespace Tests;

use PHPUnit\Framework\Attributes\Depends;
use Ocolin\Wisdm\Wisdm;

class NetworksTest extends TestBase
{
    public function testCreate() : string|int
    {
        $result = self::createNetwork();

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
              path: '/networks/{id}',
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
            path: '/networks',
        );

        $this->globalTest( result: $result );
        $this->assertEquals( expected: 200, actual: $result->status );
        $this->assertEquals( expected: 'OK', actual: $result->status_message );
        $this->assertIsArray( actual: $result->body );
    }

    #[Depends('testCreate')]
    public function testDelete( int|string $id ) : void
    {
        $result = self::deleteNetwork( $id );
        $this->globalTest( result: $result );
        $this->assertEquals( expected: 200, actual: $result->status );
        $this->assertEquals( expected: 'OK', actual: $result->status_message );
        $this->assertIsObject( actual: $result->body );
    }
}