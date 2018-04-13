<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FileTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testFileControllers()
    {
        //$this->assertTrue(true);
        $response = $this->action('POST', 'FileController@store', ['name' => 'Test File Controller', 'file' => 'somefile']);
       // $this->assertTrue($response->isOk());

    }
}
