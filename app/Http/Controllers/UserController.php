<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * TODO
     * 
     * User account creation
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'=>'required|string',
            'email'=>'required|string|email|unique:users,email',
            'password'=>'required|string'
        ]);

        if($validator->fails()){
            return $this->sendValidationErrorResposnse($validator->errors());
        }

        $user = User::create([
            'name'=>$request->get('name'),
            'email'=>$request->get('email'),
            'password'=>bcrypt($request->get('password')),
        ]);

        return $this->sendSuccessResponse($user, 'User created successfully');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'=>'required|string|email',
            'password'=>'required|string'
        ]);
        if($validator->fails()){
            return $this->sendValidationErrorResposnse($validator->errors());
        }
        $user = User::where('email', $request->get('email'))->first();

        if(!$user || !Hash::check($request->get('password'), $user->password)){
            return $this->sendBadRequestResponse('Invalid email or password');                         
        }
        $accessToken = $user->createToken('userToken')->plainTextToken;
        $response = [
            'user'=>$user,
            'token'=>$accessToken
        ];
        return $this->sendSuccessResponse($response, 'Login successfull');
    }

    public function logout(Request $request)
    {   
        $request->user()->tokens()->delete();
        return $this->sendSuccessResponse([], 'user logged out');
    }
}
