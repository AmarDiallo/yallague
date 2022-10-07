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
            $users = User::where('role', 'seller')
                ->where('state', true)
                ->get();

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
            $request->validate([
                'name'  =>  'required',
                'phone'  =>  'required',
            ],[
                'name.required' =>  'Le nom est obligatoire',
                'quantity.required' =>  'Le numéro de téléphone est obligatoire',
            ]);
    
            $user = new User;
            $user->name = request()->name;
            $user->phone = request()->phone;
            $user->role = 'seller';
            $user->email = request()->phone;
            $user->state = true;
            $user->password = Hash::make('Yallague@2022');
            $user->save();
    
            $response = [
                'data' => $user,
                'message' => $user->name. " ajouté avec succès." 
            ];
            return response()->json($response);
        }
        catch(ErrorException $e)
        {
            header("error", true, 422);
            return response()->json($e->getMessage(), 422);
        }
    }

    function editUser(Request $request, $id) 
    {
        try 
        {
            $request->validate([
                'name'  =>  'required',
                'phone'  =>  'required',
            ],[
                'name.required' =>  'Le nom est obligatoire',
                'quantity.required' =>  'Le numéro de téléphone est obligatoire',
            ]);

            $user = User::find($id);
            if($user) {
                $user->name = request()->name;
                $user->phone = request()->phone;
                $user->role = 'seller';
                $user->email = request()->phone;
                $user->state = true;
                $user->password = Hash::make('Yallague@2022');
                if($user->update()) {
                    $response = [
                        'data' => $user,
                        'message' => $user->name. " Modifé avec succès." 
                    ];
                    return response()->json($response);
                }
                throw new ErrorException("Erreur de traitement.");
            }
            throw new ErrorException("Cet utilisateur n'exsite pas.");
    
        }
        catch(ErrorException $e)
        {
            header("error", true, 422);
            return response()->json($e->getMessage(), 422);
        }
    }

    function deleteUser(Request $request, $id) 
    {
        try 
        {
            $user = User::find($id);
            if($user) {
                $user->state = false;
                if($user->update()) {
                    $response = [
                        'data' => $user,
                        'message' => $user->name. " bloqué avec succès." 
                    ];
                    return response()->json($response);
                }
                throw new ErrorException("Erreur de traitement.");
            }
            throw new ErrorException("Cet utilisateur n'exsite pas.");
    
        }
        catch(ErrorException $e)
        {
            header("error", true, 422);
            return response()->json($e->getMessage(), 422);
        }
    }
}
