<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Gate::any(['owner'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        return UserResource::collection(User::get());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        if (!Gate::any(['owner'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        return new UserResource($user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        if (!Gate::any(['owner'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'gender' => 'required',
            'address' => 'required',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:8',
            'role' => 'required',
            'image' => 'image|file|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 406);
        }

        if ($request->hasFile("image")) {
            if ($user->image != null) {
                Storage::delete($user->image);
            }
            $image = $request->file('image');
            $imageName = Str::random(40) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/users'), $imageName);
            // $path = public_path('images/users/') . $imageName;
            $user->update(
                $request->except("_method"),
            );
            $user->image = $imageName;
            $user->save();
        } else {
            $user->update($request->all());
        }
        return new UserResource(User::find($user->id));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if (!Gate::any(['owner'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        if ($user == null) {
            return response()->json(["success" => false, 'message' => 'Not found'], 404);
        }
        $user->delete();
        return response()->json(["success" => "deleted"], 204);
    }
}
