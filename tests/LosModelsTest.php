<?php

declare( strict_types =  1 );

namespace Tests;

use PHPUnit\Framework\Attributes\Depends;
use Ocolin\Wisdm\Wisdm;

class LosModelsTest extends TestBase
{

/* CREATE LOS MODEL
----------------------------------------------------------------------------- */

    /**
     * @return int ID of LOS model.
     */
    public function testCreate() : int
    {
        $result = self::createLosModel();
        $this->globalTest( result: $result );
        $this->assertEquals( expected: 201, actual: $result->status );
        $this->assertEquals( expected: 'Created', actual: $result->status_message );
        $this->assertIsObject( actual: $result->body );

        return $result->body->id;
    }


/* UPDATE LOS MODEL
----------------------------------------------------------------------------- */

    /**
     * @param int $id ID of LOS model.
     * @return void
     */
    #[Depends('testCreate')]
    public function testUpdate( int $id ) : void
    {
        $wisdm = new Wisdm();
        $result = $wisdm->call(
              path: '/los-models/{id}',
            method: 'PATCH',
            params: [ 'id' => $id ],
              body: [ 'name' => 'PHPUnit_Update' ]
        );
        $this->globalTest( result: $result );
        $this->assertEquals( expected: 200, actual: $result->status );
        $this->assertEquals( expected: 'OK', actual: $result->status_message );
        $this->assertIsObject( actual: $result->body );
    }



/* GET LOS MODEL
----------------------------------------------------------------------------- */

    /**
     * @param int $id ID of LOS model.
     * @return void
     */
    #[Depends('testCreate')]
    public function testGet( int $id ) : void
    {
        $wisdm = new Wisdm();
        $result = $wisdm->call(
            path: '/los-models/{id}',
            params: [ 'id' => $id ]
        );
        $this->globalTest( result: $result );
        $this->assertEquals( expected: 200, actual: $result->status );
        $this->assertEquals( expected: 'OK', actual: $result->status_message );
        $this->assertIsObject( actual: $result->body );
    }



/* GET ALL LOS MODELS
----------------------------------------------------------------------------- */

    public function testGetAll() : void
    {
        $wisdm = new Wisdm();
        $result = $wisdm->call( path: '/los-models' );
        $this->globalTest( result: $result );
        $this->assertEquals( expected: 200, actual: $result->status );
        $this->assertEquals( expected: 'OK', actual: $result->status_message );
        $this->assertIsArray( actual: $result->body );
    }



/* DELETE LOS MODELS
----------------------------------------------------------------------------- */

    /**
     * @param int $id ID of LOS model.
     * @return void
     */
    #[Depends('testCreate')]
    public function testDelete( int $id ) : void
    {
        $result = self::deleteLosModels( id: $id );
        $this->globalTest( result: $result );
        $this->assertEquals( expected: 200, actual: $result->status );
        $this->assertEquals( expected: 'OK', actual: $result->status_message );
        $this->assertIsObject( actual: $result->body );
    }
}