<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Register
     * 
     * @unauthenticated
     * 
     * @response UserResource
     */
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        $token = JWTAuth::fromUser($user);

        return (new UserResource($user))->additional([
            'token' => $token
        ]);
    }

    /**
     * Login
     * 
     * @unauthenticated
     * 
     * @response UserResource
     */
    public function login(LoginRequest $request)
    {
        $user = User::firstWhere('username', $request->input('username'));
        $creds = $request->only('password');
        $creds['email'] = $user->email;

        try {
            if (!JWTAuth::attempt($creds)) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }

            $user = $request->user();

            $token = JWTAuth::claims(['role' => $user->role])->fromUser($user);

            return (new UserResource($user))->additional([
                'token' => $token
            ]);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }
    }

    /**
     * Logout
     */
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'Logged out successfully']);
    }
}
