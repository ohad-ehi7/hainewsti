<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Symfony\Contracts\Service\Attribute\Required;

class AuthorLoginForm extends Component
{
    // Déclarer les propriétés utilisées pour contenir les identifiants de connexion
    public $login_id, $password;

    // Cette fonction gère la soumission de connexion
    public function LoginHandler(){
      
      // Déterminez si l'identifiant de connexion est un e-mail ou un nom d'utilisateur
      $fieldType = filter_var($this->login_id, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
      
      // Si l'identifiant de connexion est un e-mail, validez-le en conséquence
      if($fieldType == 'email'){
         $this->validate([
            'login_id' =>'required|email|exists:users,email', // Assurez-vous qu'il s'agit d'un e-mail valide et qu'il existe dans la base de données
            'password' => 'required|min:5', // Assurez-vous que le mot de passe est fourni et a une longueur minimale de 5 caractères

         ],[
            'login_id'=>'Email or Username is required', // Si l'e-mail est manquant, affichez cette erreur
            'login_id.email'=>'Invalid email address', // If the email is invalid, show this error
            'login_id.exists'=> 'Email is not registered', // If the email doesn't exist in the database, show this error
            'password.required' => 'Password is required', // If the password is missing, show this error
         ]);

      // If the login ID is a username, validate it accordingly
      }else{
         $this->validate([
            'login_id' =>'required|exists:users,username', // Make sure the username exists in the database
            'password' => 'required|min:5', // Make sure the password is provided and has a minimum length of 5 characters

         ],[
            'login_id.required'=>'Email or Username is required', // If the username is missing, show this error
            'login_id.exists'=> 'Username is not registered', // If the username doesn't exist in the database, show this error
            'password.required' => 'Password is required', // If the password is missing, show this error
         ]);

      }
       
      // Combine the login ID and password into an array
      $creds = array($fieldType=>$this->login_id,'password'=>$this->password);
       
      // Attempt to authenticate the user using the combined credentials
      if(Auth::guard('web')->attempt($creds)){
          
          // If the user is authenticated, check if their account is blocked
          $checkUser = User::where($fieldType,$this->login_id)->first();
          if($checkUser->blocked==1){
              
              // If the account is blocked, log the user out and show an error message
              Auth::guard('web')->logout();
              return redirect()->route('author.login')->with('fail','You Account has been blocked.');
              
          }else{
              
              // If the account is not blocked, redirect the user to the home page
             return redirect()->route('author.home');
          }
          
       }else{
           
           // If the authentication fails, show an error message
         session()->flash('fail','Incorrect Email/Username or Password');
       }
    




      //   $this->validate([
      //     'email'=>'Required|email|exists:users,email',
      //     'password'=>'Required|min:5'
      //   ],[
      //      'email.required'=>'Entre your email address',
      //      'email.email'=>'Invalid email address',
      //      'email.exists'=>'This email is not registered in database',
      //      'password.required'=>'Password is required'
      //   ]);  
      //    $creds =array('email'=>$this->email, 'password'=>$this->password); 
      //     if(Auth::guard('web')->attempt($creds)){

      //      $checkUser = User::where('email',$this->email)->first();
      //       if($checkUser->blocked==1){
      //          Auth::guard('web')->logout();
      //          return redirect()->route('author.login')->with('fail','Your account has been bloked');

      //       }else{
      //          return redirect()->route('author.home');
                  
      //       } 
            
      //     }else{
      //    session()->flash('fail','Incorrect email or password');
      //     }  
    
    }
    
    public function render()
    {
        return view('livewire.author-login-form');
    }
}
