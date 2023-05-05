<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
  
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index(Request $request){
        return view('back.pages.home');
    }
    public function logout(){
        Auth::guard('web')->logout();
        return redirect()->route('author.login');
    }
}
