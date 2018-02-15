<?php

namespace Tests;

class ExampleTest extends TestCase
{
    public function testTruthiness()
    {
        $this->assertTrue(true);
    }

    public function testBasic()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
