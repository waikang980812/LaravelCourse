<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

class UserAccountController extends Controller
{
    public function create(){
        return inertia('UserAccount/Create');
    }

    public function store(Request $request){
        $user = User::create($request->validate([
            'name'=>'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|confirmed'
        ]));
        // $user->password = Hash::make($user->password); //did it in the model

        // $user->save();

        Auth::login($user);
        event(new Registered($user));

        return redirect()->route('listing.index')->with('success','Account created!');
    }
}
