<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Symfony\Contracts\Service\Attribute\Required;

class AuthorResetForm extends Component
{
    public $email, $token, $new_password, $confirm_new_password;
     
    public function mount()
    {
        // Récupérer les valeurs de l'URL
        $this->email = request()->email;
        $this->token = request()->token;
    }
    
    public function ResetHandler()
    {
        // Valider les champs du formulaire
        $this->validate([
            'email' => 'required|email|exists:users,email',
            'new_password' => 'required|min:5',
            'confirm_new_password' => 'same:new_password',
        ], [
            'email.required' => 'The email field is required',
            'email.email' => 'Invalid email address',
            'email.exists' => 'The email is not registered',
            'new_password.required' => 'Enter new password',
            'new_password.min' => 'Minimum characters must be 5',
            'confirm_new_password.same' => 'The confirm new password and new password must match',
        ]);
        
        // Vérifier le jeton de réinitialisation
        $check_token = DB::table('password_resets')->where([
            'email' => $this->email,
            'token' => $this->token,
        ])->first();

        if (!$check_token) {
            session()->flash('fail', 'Invalid Token');
        } else {
            // Mettre à jour le mot de passe de l'utilisateur
            User::where('email', $this->email)->update([
                'password' => Hash::make($this->new_password),
            ]);
            
            $success_token = Str::random(64);
            
            session()->flash('success', 'Your password has been successfully. login with your email (<span>' . $this->email . '</span>) and your new password');
            
            // Rediriger vers la page de connexion avec les paramètres du jeton et de l'e-mail
            $this->redirectRoute('author.login', ['tkn' => $success_token, 'UEmail' => $this->email]);
        }
    }
    
    public function render()
    {
        return view('livewire.author-reset-form');
    }
}
