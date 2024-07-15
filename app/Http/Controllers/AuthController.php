<?php

namespace App\Http\Controllers;

use App\Models\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'full_name' => 'required',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::create([
                'full_name' => $request->full_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone_number' => '012345678',
                'role' => 'user',
                'address' => 'Earth',
                'is_verified' => false
            ]);

            $this->sendVerifyMail($user->email);
            return response()->json([
                'status' => true,
                'message' => 'User created successfully. Please check your email for verification.'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
       try {
        $validateUser = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'password' => 'required'
            ]
        );

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }

        if (!Auth::attempt($request->only(['email', 'password']))) {
            return response()->json([
                'status' => false,
                'message' => 'Email & Password do not match with our records.',
            ], 401);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user->is_verified) {
            return response()->json([
                'status' => false,
                'message' => 'Please verify your email before logging in.'
            ], 403);
        }

        return response()->json([
            'status' => true,
            'message' => 'User Logged In Successfully',
            'token' => $user->createToken("API TOKEN")->plainTextToken
        ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out'
        ]);
    }

    public function forgetPassword(Request $request)
    {
        try {
            $user = User::where('email', $request->email)->get();
            if (count($user) > 0) {

                $token = Str::random(40);
                $domain = URL::to('/');
                $url = $domain.'/reset-password?token='.$token;
                $data['url'] = $url;
                $data['email'] = $request->email;
                $data['title'] = "Reset Password";
                $data['body'] = "Please click on below link to reset your password";

                Mail::send('forgetPassword', ['data' => $data], function ($message) use ($data) {
                    $message->to($data['email'])->subject($data['title']);
                });
                $datetime = Carbon::now()->format('Y-m-d H:i:s');
                PasswordReset::updateOrCreate(
                    ['email' => $request->email],
                    [
                        'email' => $request->email,
                        'token' => $token,
                        'created_at' => $datetime
                    ]
                );
                return response()->json([
                    'status' => true,
                    'message' => 'Please check your email to reset password!'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found!'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function resetPasswordLoad(Request $request)
    {
        

        $resetData= PasswordReset::where('token',$request->token)->first();
        // dd($resetData); 
        // if(isset($request->token) && count($resetData)>0){
            $user = User::where('email', $resetData->email)->first();
            // dd($user); 
             return view('resetPassword',compact('user'));
      
    }
    public function resetPassword(Request $request)  {
        $request->validate([
            'password'=>'required|string|min:6|confirmed'
        ]);
        $user = User::find($request->id);
        $user->password = $request->password;
        $user->save();
        return "<h1>Your password has been reset successfully!</h1>";
        
    }

    public function sendVerifyMail($email)
    {
        $user = User::where('email', $email)->first();
        if ($user) {
            $token = Str::random(40);
            $user->remember_token = $token;
            $user->save();

            $domain = URL::to('/');
            $url = $domain . '/api/verify-email?token=' . $token;
            $data['url'] = $url;

            $data['email'] = $email;
            $data['title'] = "Email Verification";
            $data['body'] = "Please click on below link to verify your email!";
            Mail::send('verifyMail', ['data' => $data], function ($message) use ($data) {
                $message->to($data['email'])->subject($data['title']);
            });

            return response()->json([
                'status' => true,
                'message' => "Verification email sent"
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "User not found"
            ]);
        }
    }


    public function verifyEmail(Request $request)
    {
        $token = $request->query('token');
        $user = User::where('remember_token', $token)->first();
    
        if ($user) {
            $user->is_verified = true;
            $user->email_verified_at = Carbon::now();
            $user->remember_token = null;
            $user->save();
    
            return response()->json([
                'status' => true,
                'message' => 'Email verified successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Invalid token'
            ]);
        }
    }
    
}
