<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use ErrorException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    function login(Request $request)
    {
        try {
            $user = User::where('phone', $request->phone)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                throw new ErrorException("Les identifiants de connexion sont invalides.");
            }

            $token = $user->createToken('my-app-token')->plainTextToken;

            $response = [
                'user' => $user,
                'token' => $token,
                'code' => 201
            ];

            return response($response, 201);
        } catch (ErrorException $e) {
            header("error", true, 422);
            return response()->json($e->getMessage(), 422);
        }
    }

    function getUsers() 
    {
        try 
        {
            $users = User::all();

            return response()->json($users, 200);
        }
        catch(ErrorException $e)
        {
            header("error", true, 422);
            return response()->json($e->getMessage(), 422);
        }
    }

    function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(["message" => "Vous êtes deconnecté !"]);
    }

    function createUser(Request $request) 
    {
        try 
        {
            $request->validate(['name' => 'required']);
    
            $user = new User;
            $user->name = $request->name;
            $user->phone = $request->phone;
            $user->email = $request->email;
            $user->role = $request->role;
            $user->state = true;
            $user->password = Hash::make($request->name);
            $user->save();
    
            $response = [
                'data' => $user,
                'message' => $user->name. " ajoutée avec succès." 
            ];
            return response()->json($response);
        }
        catch(ErrorException $e)
        {
            header("error", true, 422);
            return response()->json($e->getMessage(), 422);
        }
    }
}
