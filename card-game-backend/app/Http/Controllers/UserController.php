<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),[
                'email' => 'required',
                'password' => 'required'
            ],[
                'email.required' => 'Email is required',
                'password.required' => 'Password is required'
            ]);
            if($validator->fails()) return response()->json($validator->errors(),422);

            $checkEmail = User::where('email',$request->email)->first();

            if(!$checkEmail) {
                User::create([
                    'email' => $request->email,
                    'password' => Hash::make($request->password)
                ]);

                return response()->json(['message'=> 'User Created Successfully']);
            }
            return response()->json(['message'=> 'An User with same email already exists']);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
