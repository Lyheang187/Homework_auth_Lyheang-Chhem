<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    
    public function index() {
        return User::latest()->get();
    }

    public function register(Request $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);

        $token = $user->createToken('MyToken')->plainTextToken;

        $user->save();

        return response()->json([
            'Message' => 'Created',
            'data' => $user,
            'token' =>  $token
        ], 201);
    }

    public function signout(Request $request) 
    {
        auth()->user()->tokens()->delete();

        return response()->json(['Message' => 'Signing out']);
    }

    public function login(Request $request) 
    {
        $user = User::where('email', $request->email)->first();
        
        if(!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(["Message" => "Bad login"], 401);
        }

        $token = $user->createToken('MyToken')->plainTextToken;

        return response()->json([
            'Message' => 'Created',
            'data' => $user,
            'token' => $token
        ], 201);
    }

}