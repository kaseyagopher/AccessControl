<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccessControlController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $nom=$user->name;
        $role=$user->role;
        $matricul=$user->matricul;

        return view('dashboard',compact('user'));

    }
}
