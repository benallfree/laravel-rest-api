<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PingTest extends TestCase
{
    public function testPing()
    {
      $response = $this->json('GET', '/api/v1/ping');
      $response
        ->assertStatus(200)
        ->assertJson(['status'=>'ok'])
      ;
    }
}
