<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Client;
use App\Notifications\NewUserEmail;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (!Auth::guard('client')->attempt($request->all())) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $user = Auth::guard('client')->user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token]);
    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:clients',
            'password' => 'required|min:6',

        ]);

    if ($validator->fails()) {
            $responseArr['message'] = $validator->errors();;
            $responseArr['token'] = '';
            return response()->json($responseArr, Response::HTTP_BAD_REQUEST);
        }
        $client = Client::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $adminEmail = env('ADMIN_EMAIL');
        Notification::route('mail', $adminEmail)->notify(new NewUserEmail($client));
        return response()->json(['success', 'User created']);
    }
}
