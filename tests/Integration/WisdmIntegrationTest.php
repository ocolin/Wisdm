<?php

declare( strict_types=  1 );

namespace Ocolin\Wisdm\Tests\Integration;

use Ocolin\Wisdm\Wisdm;
use Ocolin\Wisdm\Response;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;
use Ocolin\EasyEnv\Env;

class WisdmIntegrationTest extends TestCase
{
    private static Wisdm $wisdm;

    private static string|int|null $id;

    public function testCreateNetwork() : void
    {
        $result = self::$wisdm->post(
            endpoint: '/networks',
            body: [
                'name' => 'PHPUnit_Test',
                'colour' => '#808080'
            ]
        );
        $this->assertEquals( expected: 201, actual: $result->status );
        $this->assertIsObject( actual: $result->body );

        self::$id = $result->body->id;
    }

    public function testPatchNetwork() : void
    {
        $result = self::$wisdm->patch(
            endpoint: '/networks/{id}',
            query: [ 'id' => self::$id ],
            body: [ 'name' => 'PHPUnit_Test_Update' ]
        );
        $this->assertEquals( expected: 200, actual: $result->status );
        $this->assertIsObject( actual: $result->body );
        $this->assertSame( 'PHPUnit_Test_Update', $result->body->name );
    }


    public function testGetNetwork() : void
    {
        $result = self::$wisdm->get( endpoint: '/networks' );
        $this->assertEquals( expected: 200, actual: $result->status );
        $this->assertIsArray( actual: $result->body );
        $this->assertNotEmpty( actual: $result->body );
    }




    public function testDeleteNetwork() : void
    {
        $result = self::deleteNetwork();

        $this->assertEquals( expected: 200, actual: $result->status );
        $this->assertIsObject( actual: $result->body );

        self::$id = null;
    }


    public static function setUpBeforeClass(): void
    {
        Env::load( files: __DIR__ . '/../../.env' );
        self::$wisdm = new Wisdm();
    }

    public static function tearDownAfterClass(): void
    {
        if( self::$id === null) {
            self::deleteNetwork();
        }

        self::$id = null;
    }

    private static function deleteNetwork() : Response
    {
        return self::$wisdm->delete(
            endpoint: '/networks/{id}',
            query: ['id' => self::$id ]
        );
    }
}