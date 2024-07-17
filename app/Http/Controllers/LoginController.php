<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Auth;

class LoginController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->stateless()->user();
            
            $finduser = User::where('google_id', $user->id)->first();
            
            if($finduser){
                Auth::login($finduser);
                return redirect("http://localhost:5500/test.html?email=" .  $user->email);
            } else {
                $newUser = User::create([
                    'full_name' => $user->name,
                    'email' => $user->email,
                    'google_id'=> $user->id,
                    'password' => encrypt('123456dummy'),
                    'phone_number' => "0123456jqk",
                    'role'=>'user_google',
                    'address'=>'Earth'
                ]);
                
                Auth::login($newUser);
               
               
                return redirect(`http://localhost:5500/test.html?email=` . $user->email);
            }

        } catch (Exception $e) {
            return redirect('auth/google');
        }
    }
    
}