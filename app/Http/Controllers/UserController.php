<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function get($id)
    {
        $data = User::find($id);
        return new UserResource($data);
    }


    public function index(Request $request)
    {
        $searchTerm = $request->search;
        $data = User::where("role_id", "!=", "2")
            ->where(function ($query) use ($searchTerm) {
                $query->where('username', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('name', 'LIKE', '%' . $searchTerm . '%');
            })->orderBy('created_at', 'DESC')->paginate($request->total ?? 10);
        return UserResource::collection($data);
    }


    public function store(Request $request)
    {
        $credentials = $request->validate([
            'name' => 'required',
            'username' => 'required',
            'password' => 'required',
            'role_id' => 'required',
            // 'image' => 'file|mimes:png,jpg,jpeg|max:4086',
        ]);
        $check = User::where('username', $request->username)
            ->first();
        if ($check) {
            return response()->json([
                "message" => "failed user already exist"
            ], 409);
        }
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role_id' =>  $request->role_id,
        ]);
        return new UserResource($user);
    }


    public function edit(Request $request, $id)
    {
        $credentials = $request->validate([
            'name' => 'required',
            'username' => 'required',
            // 'image' => 'file|mimes:png,jpg,jpeg|max:4086',
        ]);
        $check = User::find($id);
        if (!$check) {
            return response()->json([
                "message" => "failed user not exist"
            ], 404);
        }
        if ($request->password) {
            $check = User::find($id);
            $check->update([
                'password' => Hash::make($request->password),
            ]);
        }
        $check = User::find($id);
        $user = $check->update([
            'name' => $request->name,
            'username' => $request->username,
            'role_id' =>  $request->role_id,
        ]);
        $check = User::find($id);
        return new UserResource($check);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return response()->json([
            'message' => 'Berhasil hapus User',
            'data' => $user,
        ], 200);
    }
}
