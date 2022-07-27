<?php

namespace App\Http\Services\User;

use App\Models\api\v1\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class UserService
{
    public function register($request)
    {
        try {
            User::create([
                'name' => (string) $request->input('name'),
                'email' => (string) $request->input('email'),
                'password' => bcrypt((string) $request->input('password')),
            ]);

        } catch (\Exception $err){
            return response()->json($err, 400);
        }
    }
}
