<?php

namespace App\Http\Controllers;
use App\Libraries\RBMQSender;
use Illuminate\Http\Request;
use App\User;
use App\model\Notes;
use Illuminate\Support\Facades\Redis;


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
        
        $rabbitmq = new RBMQSender();

        $toEmail = 'shaikfiroz838@gmail.com';
        // $toEmail=$validatedData['email'];
        $subject = "Please verify  email for register";
        $message = "Hi  \nThis is email verification mail from Fundoo.
        \nFor complete registration process verify you email by click this link.
        \n" . url('/') . "/api/verifyMail/" . $token . "
        \nThanks.";

        if ($rabbitmq->sendMail($toEmail, $subject, $message)) {
            return response(['success' => 'registerd successfully']);
        } else {
            return response()->json(['successBut' => 'error while sending link to mail']);
        }
    }

    public function verifyMail($token)
    {
        $tokenArray = preg_split("/\./", $token);
        $decodetoken = base64_decode($tokenArray[1]);
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
            \n" . url('/') . "/resetPassword/" . $token ."
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
        $validator = $request['password'];

        $tokenArray = preg_split("/\./", $token);
        $decodetoken = base64_decode($tokenArray[1]);
        $decodetoken = json_decode($decodetoken, true);
        $id = $decodetoken['sub'];

        $user = User::where('id',1)->get('name');
        return response()->json(['id' => $user]);
        if ($user) {
            // $user->password = bcrypt($validator['password']);
            return response()->json(['id' => $user]);   
            $user->save();
            return response()->json(['message' => 'Password is changed'], 200);
        } else {
            return response()->json(['message' => 'unknown']);
        }
    }

    public function userDetails(Request $request)
    {
        $id=ApiAuthController::convertJwtToId($request['token']);
        $find=User::find($id);
        if($find){
            return response()->json(['data' => $find]);
        }
        return response()->json(['data' => 'error']);
    }

    public function updateProfile(Request $request)
    {
        $id=ApiAuthController::convertJwtToId($id);
        $find=User::find($id);
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
        $user = Notes::find($request['id']);
        if ($user) {
            $user->collaborator=$request['email'];
            $user->save();
            return response()->json(['message' => 'Collaboration is added'], 200);
        } else {
            return response()->json(['message' => 'Error while adding']);
        }
    }

    public function convertJwtToId($token){
        $value=app('App\Http\Controllers\AuthController')->me();
        $a1=$value->original->id;
        return $a1;
    }

    public function test(Request $request)
    {
        Redis::set('taylor', 'Taylor');
        echo "ok";
    }
}
