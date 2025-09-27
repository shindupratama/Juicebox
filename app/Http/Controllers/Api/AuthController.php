<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function users()
    {
        $users = User::latest()->paginate(10);

        return response()->json([
            'success' => true,
            'users'   => $users,
        ], 201);
    }

    public function user(string $id)
    {
        //find user by ID
        $user = User::find($id);

        //response if there is no user
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User is not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'user'    => $user,
        ], 201);
    }

    public function register(Request $request)
    {
        // validate request
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|max:255|unique:users',
            'password'  => 'required|min:8'
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // create new user
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // create token
        $token = $user->createToken('auth_token')->plainTextToken;

        //return response if user is created
        if ($user) {
            return response()->json([
                'success' => true,
                'token'   => $token,
                'user'    => $user,
            ], 201);
        }

        //return response if insert failed 
        return response()->json([
            'success' => false,
            'message' => 'Registration failed.'
        ], 409);
    }

    public function login(Request $request)
    {
        // validate request
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // attempt to login user
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'These credentials do not match our records.'
            ], 404);
        }

        // create token
        $token = $user->createToken('auth_token')->plainTextToken;

        //return response if user is logged in
        return response()->json([
            'success' => true,
            'token'   => $token,
            'user'    => $user,
        ], 201);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json([
            'success' => true,
            'message' => 'You are logged out successfully'
        ], 200);
    }
}
