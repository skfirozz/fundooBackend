<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\model\Notes;

class ApiAuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'firstName' => 'required|max:55',
            'lastName' => 'required|max:55',
            'email' => 'email|required|unique:users',
            'password' => 'required'
        ]);

        $validatedData = array();
        $validatedData['name'] = $validated['firstName'] . " " . $validated['lastName'];
        $validatedData['email'] = $validated['email'];
        $validatedData['password'] = $validated['password'];
        $validatedData['password'] = bcrypt($validatedData['password']);
        $user = User::create($validatedData);
        
        $token = $user->createToken('SECRETKEY')->accessToken;

        // $rabbitmq = new RBMQSender();

        // $toEmail = 'shaikfiroz838@gmail.com';
        // // $toEmail=$validatedData['email'];
        // $subject = "Please verify email for register";
        // $message = "Hi  \nThis is email verification mail from Fundoo.
        // \nFor complete registration process verify you email by click this link.
        // \n" . url('/') . "/api/verifyMail/" . $token . "
        // \nThanks.";

        // if ($rabbitmq->sendMail($toEmail, $subject, $message)) {
            return response(['user' => $user, 'access_token' => $token]);
        // } else {
            // return response()->json(['success' => 'success', 'message' => 'Registerd successfully.']);
        // }
    }

    public function verifyMail($token)
    {
        $tokenArray = preg_split("/\./", $token);
        $decodetoken = base64_decode($tokenArray[1]);
        echo $decodetoken;
        $decodetoken = json_decode($decodetoken, true);
        $user_id = $decodetoken['sub'];

        $user = User::where(['id' => $user_id])->first();
        if (!$user) {
            return response()->json(['message' => "Not a Registered Email"], 200);
        } else if ($user->email_verified_at == null) {
            $user->email_verified_at = now();
            $user->save();

            return response()->json(['message' => "Email is Successfully verified"], 201);
        } else {
            return response()->json(['message' => "Email Already verified"], 202);
        }
    }

    public function login(Request $request)
    {
        $inputvalues=$request->all();
        $data=User::where(['email' => $inputvalues['email'],'password'=>$inputvalues['password']])->get('id');
        if($data!="[]"){
            return response()->json(['message' => 'valid', 'token' => $data[0]['id']]);
        }
        else{
            return response()->json(['message' => 'invalid', 'token' => $data]);
        }
        return response()->json(['message' => 'invalid', 'token' => 'ok']);
    }

    public function forgotPassword(Request $request)
    {
        $validator = $request->validate(
            [
                'email' => 'required|email'
            ]
        );

        $input = $request->all();
        $user = User::where($input)->first();

        if ($user) {
            $token = $user->createToken('SECRETKEY')->accessToken;
            $rabbit = new RBMQSender();

            $subject = "Please verify email to reset your password";
            $message = "Hi , \nThis is email from fundoo.
            \nFor complete reset password process click this link.
            \n" . url('/') . "/api/resetPassword/" . $token . "
            \nThanks.";

            $email = 'shaikfiroz838@gmail.com';
            if ($rabbit->sendMail($email, $subject, $message)) {
                return response()->json(['success' => $token, 'message' => 'Please Check Mail for Email Verification.'], 200);
            } else {
                return response()->json(['success' => $token, 'message' => 'Error While Sending Mail.'], 400);
            }
        } else {

            return response()->json(['message' => 'Email id is not Registered'], 400);
        }
    }

    public function resetPassword(Request $request, $token)
    {
        $validator = $request->validate(
            [
                'password' => 'required|confirmed'
            ]
        );

        $tokenArray = preg_split("/\./", $token);
        $decodetoken = base64_decode($tokenArray[1]);
        $decodetoken = json_decode($decodetoken, true);
        $id = $decodetoken['sub'];

        $user = User::where(['id' => $id])->first();
        if ($user) {
            $user->password = bcrypt($validator['password']);
            $user->save();
            return response()->json(['message' => 'Password is changed'], 200);
        } else {
            return response()->json(['message' => 'unknown'], 400);
        }
    }

    public function userDetails(Request $request)
    {
        $find=User::find($request['token']);
        if($find){
            return response()->json(['data' => $find]);
        }
        return response()->json(['data' => 'error']);
    }

    public function updateProfile(Request $request)
    {
        $find=User::find($request['token']);
        if($find){
            $find->profileImage=$request['profilePic'];
            $find->save();
            return response()->json(['message'=>$find]);
        }
        else
         return response()->json(['message'=>"profile not updated"]);
    }


    public function collaborator(Request $request)
    {
        $emailExist=User::where(['email'=>$request['email']])->get('id');
        if($emailExist){
            $user = Notes::find($request['id']);
            if ($user) {
                $user->collaborator=$request['email'];
                $user->save();
                return response()->json(['message' => 'Collaboration is added'], 200);
            } else {
                return response()->json(['message' => 'Error while adding']);
            }
        }
        else{
            return response()->json(['message' => 'Unregistered user']);
        }
    }  

    public function test()
    {
        echo "Welcome to my WORLD";
    }
}