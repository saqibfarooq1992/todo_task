<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Mail;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed',
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        if($user->save()){
            return response()->json([
                'message' => 'user created successfully!',
                'status_code' => 201
            ], 201);
        }else{
            return response()->json([
                'message' => 'Some error occurred, Please try again',
                'status_code' => 500
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            return response()->json([
                'message' => 'Invalid Username Or Password',
                'status_code' => 401
            ], 401);
        }

        $user = $request->user();
        $tokenData = $user->createToken('Personal Access Token' , ['user']);

        $token = $tokenData->token;
        if($token->save()) {
            return response()->json([
                'user' => $user,
                'access_token' => $tokenData->accessToken,
                'token_type' => 'Bearer',
                'token_scope' => $tokenData->token->scopes[0],
                'status_code' => 200
            ], 200);
        } else {
            return response()->json([
                'message' => 'Some error occured, Please try again',
                'status_code' => 500
            ], 500);
        }
    }

    public function logout(Request $request){
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Logout successfully',
            'status_code' => 200
        ], 200);
    }
    public function resetPasswordRequest(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email' , $request->email)->first();
        if(!$user){
            return response()->json([
                'message'   => 'we have sent you a varification code to your email address',
                'status_code' => 200,
            ], 200);
        }else{

            $random = rand(111111,999999);
            $user->varification_code = $random;
            if($user->save()){
                $userData = array(
                    'email' => $user->email,
                    'name' => $user->name,
                    'random' => $random,
                );
                Mail::send('emails.reset_password', $userData, function ($message) use ($userData) {
                    $message->from('no_reply@techozon', 'Password Request');
                    $message->to($userData['email'], $userData['name']);
                    $message->subject('Reset Password Request (Techozone)');
                });
                    if(Mail::failures()){

                        return response()->json([
                            'message' => 'Some error occured , Please Try again',
                            'status_code' => 500,
                        ], 500);

                    }else{
                        return response()->json([
                            'message' => 'we have sent you a varification code to your email address',
                            'status_code' => 200,
                        ], 200);
                    }

            }else{
                return response()->json([
                    'message' => 'Some error occured , Please Try again',
                    'status_code' => 500,
                ], 500);
            }
        }
    }
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'varification_code' => 'required',
            'password' => 'required|confirmed|min:6',

        ]);
        $user = User::where('email' , $request->email)->where('varification_code' , $request->varification_code)->first();
        if(!$user){
            return response()->json([
                'message'   => 'User not found / Invalid code',
                'status_code' => 401,
            ], 401);
        }else{
            $user->password =bcrypt(trim($request->password));
            $user->varification_code = Null;
            if($user->save()){
                 return response()->json([
                        'message' => 'Password Updated Successfully',
                        'status_code' => 200,
                    ], 200);
            }else{
                return response()->json([
                    'message' => 'Some error occured , please try again',
                    'status_code' => 500,
                ], 500);
            }
        }
    }


}
