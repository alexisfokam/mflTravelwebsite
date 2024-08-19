<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{


    public function login (Request $request) { // si le champ => unlock else renvoie un message  =>logout()
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) 
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        $user = User::where('email', $request->email)->first();


        if ($user) {
                if($user->status == "unlock"){
                        if (Hash::check($request->password, $user->password)) {
                            $token = JWTAuth::fromUser($user);
                            $response = ['token' => $token];
                            return response(['access_token' => $response,'user' => $user],200);
                        } else {
                            $response = ["message" => "mot de passe incorrecte"];
                            return response()->json($response, 422);
                        }
                    }else{
                        $response = ["message" => "desole, votre compte a ete bloque"];
                        return response()->json($response,422);
                    }
            } else {
                $response = ["message" =>'ce compte n\'exite pas'];
                return response()->json($response, 422);
            }

    }
 // register user

 public function register (Request $request) {
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6',
        'avatar'=>'image|required'
    ]);
    if ($validator->fails())
    {
        return response(['errors'=>$validator->errors()->all()], 422);
    }
    $request['password'] = Hash::make($request['password']);
    $request['remember_token'] = Str::random(10);
    $request['type'] = 0;
    $request['status'] = "unlock";

    $image = $request->file('avatar');
    $extension = $image->getClientOriginalExtension();
    $newFileName = time().'.'.$extension;
     $image->move(public_path('avatars'), $newFileName);
    $request['avatar'] = $newFileName;

    $user = User::create($request->toArray());
    $user->avatar = $newFileName;
    $user->update();
    // $token = $user->createToken('Laravel Password Grant Client')->accessToken;
    
    $token = JWTAuth::fromUser($user);
    $response = ['token' => $token];

    // return response()->json([
    //     'access_token' => $token,
    //     'token_type' => 'bearer',
    // ]);

    return response(['access_token' => $response,'user' => $user],200);
    //mail
    // SendWelcomeUserJob::dispatch($user);
    // SendNotifUserJob::dispatch($user);
    // ResetUsersJob::dispatch($user);
    // Cache::rememberForever('allusers',function(){
    //     return User::orderBy('created_at', 'DESC')->get();
    // });
   

}

public function logout (Request $request) {
    $token = $request->user()->token();
    $token->revoke();
    $response = ['message' => 'You have been successfully logged out!'];
    // ResetUsersJob::dispatch($request->user());
    return response($response, 200);
}
// public function logout()
//     {
//         auth()->logout();

//         return response()->json(['message' => 'Successfully logged out']);
//     }

   
    public function refresh() 
    { 
        return response()->json([ 
            'user' => Auth::user(),
            'autorisation' => [ 
                'jeton' => Auth::refresh(), 
                'type' => 'porteur', 
            ] 
        ]); 
    } 
}
