<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function dashboard()
    {
        return response()->json([
            'message' => 'Welcome to the Admin Dashboard',
            'user' => auth()->user()
        ]);
    }

    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function store(Request $request)
    {
        $validateDriver = Validator::make(
            $request->all(),
            [
                'full_name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
                'phone_number' => 'required',
                'address' => 'required'
            ]
        );

        if ($validateDriver->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validateDriver->errors()
            ], 401);
        }

        $driver = User::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'role' => 'driver'
        ]);
        $this->sendVerifyMail($driver->email);

        return response()->json([
            'status' => true,
            'message' => 'Driver added successfully',
            'driver' => $driver
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        $validateUser = Validator::make(
            $request->all(),
            [
                'full_name' => 'required',
                'phone_number' => 'required',
                'address' => 'required'
            ]
        );

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }

        $user->update([
            'full_name' => $request->full_name,
            'phone_number' => $request->phone_number,
            'address' => $request->address
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User updated successfully',
            'user' => $user
        ]);
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        $user->delete();

        return response()->json([
            'status' => true,
            'message' => 'User deleted successfully'
        ]);
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
