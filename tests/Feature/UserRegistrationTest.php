<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;
use App\Mail\AccountCreated;

class UserRegistrationTest extends TestCase
{
  use DatabaseTransactions;
  
  public function testBasicRegistration()
  {
    Mail::fake();
    $this->assertDatabaseMissing('users', ['username'=>'benallfree']);
    
    $response = $this->json('POST', '/api/v1/account/create', ['username'=>'benallfree', 'password'=>'password', 'email'=>'ben@benallfree.com']);
    $response
      ->assertStatus(200)
      ->assertJson(['status'=>'ok', 'user'=>[]]);
    ;
    $data = $response->getData();
    $this->assertTrue(is_object($data->user));
    $this->assertTrue(is_numeric($data->user->id));
    $this->assertTrue(is_string($data->user->username));
    $this->assertDatabaseHas('users', (array)$data->user);
    $u = User::find($data->user->id);
    Mail::assertQueued(AccountCreated::class, function ($mail) use ($u) {
      return $mail->user->id === $u->id;
    });
  }

  public function testEmptyUsername()
  {
    $response = $this->json('POST', '/api/v1/account/create', ['password'=>'password', 'email'=>'ben@benallfree.com']);
    $response
      ->assertStatus(200)
      ->assertJson(['status'=>'error', 'errors'=>['username'=>[]]]);
    ;
    $data = $response->getData();
  }
  
  public function testBadUsername()
  {
    $response = $this->json('POST', '/api/v1/account/create', ['username'=>'  $ ', 'password'=>'password', 'email'=>'ben@benallfree.com']);
    $response
      ->assertStatus(200)
      ->assertJson(['status'=>'error', 'errors'=>['username'=>[]]]);
    ;
    $data = $response->getData();
  }

  public function testEmptyPassword()
  {
    $response = $this->json('POST', '/api/v1/account/create', ['username'=>'  $ ', 'password'=>'', 'email'=>'ben@benallfree.com']);
    $response
      ->assertStatus(200)
      ->assertJson(['status'=>'error', 'errors'=>['password'=>[]]]);
    ;
    $data = $response->getData();
  }

  public function testShortPassword()
  {
    $response = $this->json('POST', '/api/v1/account/create', ['username'=>'  $ ', 'password'=>'234', 'email'=>'ben@benallfree.com']);
    $response
      ->assertStatus(200)
      ->assertJson(['status'=>'error', 'errors'=>['password'=>[]]]);
    ;
    $data = $response->getData();
  }

}
