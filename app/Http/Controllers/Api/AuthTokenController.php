<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthTokenController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'email'       => ['required','email'],
            'password'    => ['required','string'],
            'device_name' => ['nullable','string','max:100'],
        ]);

        if (! Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user  = $request->user();
        $name  = $data['device_name'] ?? 'api';
        $token = $user->createToken($name, ['*'])->plainTextToken;

        return response()->json([
            'token' => $token,
            'type'  => 'Bearer',
            'user'  => ['id'=>$user->id,'name'=>$user->name,'email'=>$user->email],
        ], 201);
    }

    public function destroy(Request $request)
    {
        $request->user()->currentAccessToken()?->delete();
        return response()->json(['message' => 'Token revoked.']);
    }
}
