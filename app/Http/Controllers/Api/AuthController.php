<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Login user and generate API token
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Define token abilities based on user role
        $abilities = $user->isAdmin() 
            ? ['admin:*'] // Admin has full access
            : ['user:read']; // Regular users have read access

        // Generate token with abilities
        $token = $user->createToken(
            'api-token',
            $abilities,
            now()->addDays(30) // Token expires in 30 days
        )->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
            'token' => $token,
            'abilities' => $abilities,
        ]);
    }

    /**
     * Get authenticated user details
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function user(Request $request)
    {
        $user = $request->user();
        
        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
            'abilities' => $request->user()->currentAccessToken()->abilities,
        ]);
    }

    /**
     * Logout user and revoke token
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        // Revoke current token
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout successful',
        ]);
    }

    /**
     * Revoke all tokens for the authenticated user
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logoutAll(Request $request)
    {
        // Revoke all user tokens
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'All tokens revoked successfully',
        ]);
    }
}
