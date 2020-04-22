<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

class AuthController extends Controller
{
    public function login()
   {
       $credentials = request(['email', 'password']);
 
       if (Auth::attempt($credentials)) {
        $user = Auth::user();
        $token = $user->createToken('SECRETKEY')->accessToken;
        if ($user->email_verified_at != null) {
            return response()->json(['message' => 'valid', 'token' => $token], 200);
        } else {
            return response()->json(['message' => 'email is not verified'], 400);
        }
    } else {
        return response()->json(['message' => 'Invalid'], 400);
    }
   }
 
   /**
    * Get the authenticated User.
    *
    * @return \Illuminate\Http\JsonResponse
    */
   public function me()
   {
       return response()->json(auth()->user());
   }
 
   /**
    * Log the user out (Invalidate the token).
    *
    * @return \Illuminate\Http\JsonResponse
    */
   public function logout()
   {
       auth()->logout();
 
       return response()->json(['message' => 'Successfully logged out']);
   }
 
   /**
    * Refresh a token.
    *
    * @return \Illuminate\Http\JsonResponse
    */
   public function refresh()
   {
       return $this->respondWithToken(auth()->refresh());
   }
 
   /**
    * Get the token array structure.
    *
    * @param  string $token
    *
    * @return \Illuminate\Http\JsonResponse
    */
   protected function respondWithToken($token)
   {
       return response()->json([
           'access_token' => $token,
           'token_type' => 'bearer',
           'expires_in' => auth()->factory()->getTTL() * 60
       ]);
   }
}
