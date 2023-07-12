<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8',

        ]);
        if ($validator->fails()) {
            return response()->json([

                'errors' => $validator->errors()
            ], 406);
        }
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];
        if (!Auth::guard('web')->attempt($credentials)) {
            return response()->json([
                'errors' => 'Incorrect Password or Email'
            ], 401);
        }
        $user = User::where('email', $request['email'])->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        $data = [
            'token' => $token,
            'data' => new UserResource($user)
        ];
        return response()->json(['success' => true, 'data' => $data]);
    }
}
