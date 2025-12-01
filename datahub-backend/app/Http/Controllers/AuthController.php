<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

     /*Login user and create token Mengembalikan JSON 401 alih-alih melempar exception.*/
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'The provided credentials are incorrect.',
            ], 401); 
        }

        // Create token
        $token = $user->createToken('auth_token')->plainTextToken;

        // Log activity
        $this->activityLogService->log(
            'login',
            'User logged in',
            $user->id,
            $request
        );

        return response()->json([
            'message' => 'Login successful',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
            'token' => $token,
        ], 200);
    }

    /*Logout user (revoke token)*/
    public function logout(Request $request)
    {
        // Log activity before logout
        $this->activityLogService->log(
            'logout',
            'User logged out',
            $request->user()->id,
            $request
        );
        // Revoke current token
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logout successful',
        ], 200);
    }
}