<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // Lấy thông tin cá nhân của người dùng
    public function getUserDetails(Request $request)
    {
        $user = Auth::user();
        
        return response()->json([
            'status' => true,
            'user' => $user
        ], 200);
    }

    // Cập nhật thông tin cá nhân của người dùng
    public function updateUserProfile(Request $request)
    {
        $user = Auth::user();
    
        $validateUser = Validator::make(
            $request->all(),
            [
                'full_name' => 'sometimes|string|max:255',
                'phone_number' => 'sometimes|string|max:15',
                'address' => 'sometimes|string|max:255',
                'date_of_birth' => 'sometimes|date',
                'avatar' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]
        );
    
        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }
    
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }
    
        if ($request->has('full_name')) {
            $user->full_name = $request->full_name;
        }
    
        if ($request->has('phone_number')) {
            $user->phone_number = $request->phone_number;
        }
    
        if ($request->has('address')) {
            $user->address = $request->address;
        }
    
        if ($request->has('date_of_birth')) {
            $user->date_of_birth = $request->date_of_birth;
        }
    
        $user->save();
    
        return response()->json([
            'status' => true,
            'message' => 'User details updated successfully',
            'user' => $user
        ], 200);
    }
    
    
    // Thay đổi mật khẩu của người dùng
    public function changePassword(Request $request)
    {
        $validatePassword = Validator::make(
            $request->all(),
            [
                'current_password' => 'required',
                'new_password' => 'required|string|min:6|confirmed',
            ]
        );

        if ($validatePassword->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validatePassword->errors()
            ], 401);
        }

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Current password does not match'
            ], 401);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Password changed successfully'
        ], 200);
    }
}
