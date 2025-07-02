<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Http\Controllers\LevelTestController;
use App\Models\User;  
use Illuminate\Auth\Events\Registered;  
use Illuminate\Http\Request;  
use Illuminate\Support\Facades\Auth;  
use Illuminate\Support\Facades\Hash;  
use Illuminate\Validation\Rules;  

class RegisteredUserController extends Controller  
{  
    public function create()  
    {  
        return view('auth.register');  
    }  

    public function store(Request $request)  
    {  
        $request->validate([  
            'prenom' => ['required', 'string', 'max:50'],  
            'nom' => ['required', 'string', 'max:50'],  
            'adresse' => ['required', 'string', 'max:255'],  
            'dateNaiss' => ['required', 'date', 'before:today'],  
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],  
            'password' => ['required', 'confirmed', Rules\Password::defaults()],  
        ]);  

        $user = User::create([  
            'prenom' => $request->prenom,  
            'nom' => $request->nom,  
            'adresse' => $request->adresse,  
            'dateNaiss' => $request->dateNaiss,  
            'email' => $request->email,  
            'password' => Hash::make($request->password),  
        ]);  

        event(new Registered($user));  
        Auth::login($user);  

        return (new LevelTestController)->startTest();
    }  
   
}
