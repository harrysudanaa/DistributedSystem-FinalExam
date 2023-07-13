<?php

namespace App\Http\Controllers\Api\Auth;

use App\Actions\Fortify\PasswordValidationRules;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'gender' => 'required',
            'address' => 'required',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:8',
            'role' => 'required',
            'image' => 'image|file|max:2048',
        ]);
        $image = $request->file('image');
        $imageName = Str::random(40) . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images/users'), $imageName);
        // $path = public_path('images/users/') . $imageName;
        $user = User::create([
            'name' => $request->name,
            'gender' => $request->gender,
            'address' => $request->address,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'image' => $imageName,
        ]);

        $token = $user->createToken('auth_token');

        $data = [
            'token' => $token->plainTextToken,
            new UserResource($user)
        ];
        return response()->json(['success' => true, 'message' => 'Registered successfully', 'data' => $data], 201);
    }
}
