<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\ResetUsersJob;
use App\Jobs\SendWelcomeUserJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Cache;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;

class AuthController extends Controller
{


    public function login(Request $request)
    { // si le champ => unlock else renvoie un message  =>logout()
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }
        $user = User::where('email', $request->email)->first();


        if ($user) {
            if ($user->status == "unlock") {
                if (Hash::check($request->password, $user->password)) {
                    $token = JWTAuth::fromUser($user);
                    $response = ['token' => $token];
                    return response(['access_token' => $response, 'user' => $user], 200);
                } else {
                    $response = ["message" => "mot de passe incorrecte"];
                    return response()->json($response, 422);
                }
            } else {
                $response = ["message" => "desole, votre compte a ete bloque"];
                return response()->json($response, 422);
            }
        } else {
            $response = ["message" => 'ce compte n\'exite pas'];
            return response()->json($response, 422);
        }
    }
    // register user

    public function registerFrom(Request $request)

    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'avatar' => 'required'
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }
        $request['password'] = Hash::make($request['password']);
        $request['remember_token'] = Str::random(10);
        $request['type'] = 0;
        $request['status'] = "unlock";

        $image = $request->file('avatar');
        $extension = $image->getClientOriginalExtension();
        $newFileName = time() . '.' . $extension;
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
        SendWelcomeUserJob::dispatch($user);
        // SendNotifUserJob::dispatch($user);
        ResetUsersJob::dispatch($user);
        Cache::rememberForever('allusers',function(){
            return User::orderBy('created_at', 'DESC')->get();
        });
        return response(['access_token' => $response, 'user' => $user], 200);
        //mail
        // SendWelcomeUserJob::dispatch($user);
        // SendNotifUserJob::dispatch($user);
        // ResetUsersJob::dispatch($user);
        // Cache::rememberForever('allusers',function(){
        //     return User::orderBy('created_at', 'DESC')->get();
        // });


    }

    // public function logout(Request $request)
    // {
    //     $token = $request->user()->token();
    //     $token->revoke();
    //     $response = ['message' => 'You have been successfully logged out!'];
    //     ResetUsersJob::dispatch($request->user());
    //     return response($response, 200);
    // }
    /**
 * Logs the user out of the application.
 *
 * This function uses Laravel's built-in Auth facade to log the user out.
 * It calls the `logout` method of the Auth facade, which invalidates the user's session and any active tokens.
 * After logging the user out, it returns a JSON response with a success message.
 *
 * @return \Illuminate\Http\JsonResponse
 * @return array{message: string}
 */

public function logout(Request $request)
{
    $token = JWTAuth::getToken();
    
    // Vérifiez si le token existe
    if (!$token) {
        return response()->json(['error' => 'Token not provided'], 401);
    }

    try {
        // Invalider le token
        JWTAuth::invalidate($token);
        return response()->json(['message' => 'Successfully logged out']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Could not log out, please try again.'], 500);
    }
}

    // public function refresh()
    // {
    //     return response()->json([
    //         'user' => Auth::user(),
    //         'authorisation' => [
    //             'token' => Auth::refresh(),
    //             'type' => 'bearer',
    //         ]
    //     ]);
    // }


    // public function getUser(Request $request)
    // {
    //     $user = $request->user();
    //     return response()->json(compact('user'));
    // }


    public function user()
    {
        $user = Auth::user();
        return response()->json($user);
    }
}
