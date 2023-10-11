<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use app\Models\User;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'refresh', 'logout', 'register']]);
    }

    public function registerUser(Request $request){

        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
            'name' => 'required|string'
        ]);

        try {

            $user = new User([
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'name' => $request->input('name'),
                'role' => 'User'
            ]);
            $user->assignRole('user'); 
            $user->save();
           
            return response()->json(['message' => 'Registrasi berhasil'], 201);

        } catch (\Exception $e) {
            // Tangani kesalahan jika terjadi
            return response()->json(['error' => 'Gagal mendaftar: ' . $e->getMessage()], 500);
        }
       
    }

    public function registerAdmin(Request $request){

        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
            'name' => 'required|string'
        ]);

        try {

            $user = new User([
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'name' => $request->input('name'),
                'role' => 'Admin'
            ]);
            $user->assignRole('admin'); 
            $user->save();
           
            return response()->json(['message' => 'Registrasi berhasil'], 201);

        } catch (\Exception $e) {
            // Tangani kesalahan jika terjadi
            return response()->json(['error' => 'Gagal mendaftar: ' . $e->getMessage()], 500);
        }
       
    }

    public function registerMitra(Request $request){

        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
            'name' => 'required|string'
        ]);

        try {

            $user = new User([
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'name' => $request->input('name'),
                'role' => 'Mitra'
            ]);
            $user->assignRole('mitra'); 
            $user->save();
           
            return response()->json(['message' => 'Registrasi berhasil'], 201);

        } catch (\Exception $e) {
            // Tangani kesalahan jika terjadi
            return response()->json(['error' => 'Gagal mendaftar: ' . $e->getMessage()], 500);
        }
       
    }
 
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['email', 'password']);

        if (! $token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return $this->jsonResponse($token);
    }


    public function me()
    {
        return response()->json(auth()->user());
    }


    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->jsonResponse(auth()->refresh());
    }

    protected function jsonResponse($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60 * 24
        ]);
    }

}