<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request) {

        $fields= $request->validate([
            'nickname' => 'required|string|max:255',
            'given_name' => 'required|string|max:255',
            'family_name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'user_type' => 'required|in:client,staff,applicant',
            'status' => 'required|in:active,unconfirmed,suspended,banned,unknown',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);

        $fields['password'] = Hash::make($fields['password']);

        $user = User::create($fields);

        $token = $user->createToken($request->given_name);

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    public function login(Request $request) {

        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return [
                'message' => 'The provided credentials are incorrect.'
            ];
        }

        $token = $user->createToken($user->given_name);

        return [
            'user' => $user,
            'token' => $token->plainTextToken
        ];
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();

        return [
            'message' => 'You are logged out.'
        ];
    }
}
