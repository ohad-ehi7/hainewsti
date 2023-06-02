<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


class AuthorForgotForm extends Component
{
    public $email;
    
    public function ForgorHandler(){
      $this->validate([
        'email'=> 'required|email|exists:users,email'
      ],[
        'email.require'=> 'The :attribute is required ',
        'email.email'=> 'Invalide email address ',
        'email.exists'=> 'The :attribute is not registered'
      ]);

    //     $token = base64_encode(Str::random(64));
    //     DB::table('password_resets')->insert([
    //         'email'=>$this->email,
    //         'token'=>$token,
    //         'created_at'=>Carbon::now(),
    //   ]);
    $email = $this->email;

// Vérifier si l'adresse e-mail existe déjà dans la table
$existingReset = DB::table('password_resets')
    ->where('email', $email)
    ->first();

if ($existingReset) {
    // Mettre à jour le jeton et la date de création de l'entrée existante
    $token = base64_encode(Str::random(64));

    DB::table('password_resets')
        ->where('email', $email)
        ->update([
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);
} else {
    // Insérer une nouvelle entrée si l'adresse e-mail n'existe pas déjà
    $token = base64_encode(Str::random(64));

    DB::table('password_resets')->insert([
        'email' => $email,
        'token' => $token,
        'created_at' => Carbon::now(),
    ]);
}
    


      $user = User::where('email',$this->email)->first();
      $link = route('author.reset-form',['token'=>$token,'email'=>$this->email]);
      $body_message = " We are received a request to  reset the password for <b>Hainewsti</b> account associated with</b>". 
      $this->email.". <br> you can reset your password by clicking the button below.";
      $body_message.='<br>';
      $body_message.='<a href="'.$link.'" target="_blank" style="color"#fff; border-color:#22bc66;border-style:solid;border-width:10px 180px ; background-color:#22bs66;display:inline-block;text-decoration:none;border-radius:3px ;
      box-shadow:0 2px 3px rgba(0,0,0,0.16); -webkit-text-size-adjust:none;box-sizing:broder-box">
      Reset password </a>'; 
      $body_message.='</br>';
      $body_message.='If you did not request , please ignore this email';

      $data = array(
        'name'=>$user->name,
        'body_message' => $body_message,
      );
       
      // Mail::send('forgot-email-template', $data, function($message) use ($user){
      //   $message->from('noreply@exemple.com','Hainewsti');
      //   $message->to($user->email, $user->name)
      //           ->subject('Reset Password');
      // }); 




Mail::send(['text' => 'forgot-email-template'], $data, function ($message) use ($user) {
  $message->from('hainewsti@gmail.com', 'Hainewsti');
  $message->to($user->email, $user->name)->subject('Reset Password');
});
      $this->email= null;
      session()->flash('success','We have e-mailed your password reset link');
    }

    public function render()
    {
        return view('livewire.author-forgot-form');
    }
}
