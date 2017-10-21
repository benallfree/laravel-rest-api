<?php

use Illuminate\Http\Request;
use App\Mail\AccountCreated;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use App\User;

Route::prefix('/v1')->group(function() {
  Route::get('/ping', function (Request $request) {
    return response()->json(['status'=>'ok']);
  });

  Route::prefix('/account')->group(function() {
    Route::post('/create', function (Request $request) {
      $data = $request->input();
      $v = Validator::make($data, [
          'username' => 'required|string|max:255|unique:users|regex:/[A-Za-z_\-=]/',
          'email' => 'required|string|email|max:255',
          'password' => 'required|string|min:6',
        ]);
        if($v->fails())
        {
          return response()->json(['status'=>'error', 'errors'=>$v->getMessageBag()]);
        }
        $data['password']=bcrypt($data['password']);
        $u = User::create($data);
        Mail::to($u)->queue(new AccountCreated($u));
        return response()->json(['status'=>'ok', 'user'=>$u]);
    });
  });

});
