<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthorChangePasswordForm extends Component
{
    public $current_password, $new_password, $confirm_new_password;

    public function changePassword()
    {
        $this->validate([
            'current_password' => [
                'required', function ($attribute, $value, $fail) {
                    if (!Hash::check($value, User::find(auth('web')->id())->password)) {
                        $fail(__('The current password is incorrect'));
                        
                    }
                },
            ],
            'new_password' => 'required|min:5|max:25',
            'confirm_new_password' => 'same:new_password'
        ], [
            'current_password.required' => 'Enter your current password',
            'new_password.required' => 'Enter new password',
            'confirm_new_password.same' => 'The confirm password must be equal to the new password ',

        ]);
    }
    public function render()
    {
        return view('livewire.author-change-password-form');
    }
}
