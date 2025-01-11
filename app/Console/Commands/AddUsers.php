<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class AddUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add any type of user(s)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Ask for name with validation
        $name = $this->ask('What is the name?');
        while (empty($name)) {
            $this->error('Name cannot be empty. Please provide a valid name.');
            $name = $this->ask('What is the name?');
        }
    
        // Ask for role with validation
        $role = $this->choice('Which role do you want to assign?', ['Super Admin', 'Moderator', 'User'], 2);
        $role_id = null;
    
        // Define role_id based on the selected role
        if ($role == 'Super Admin') {
            $role_id = 1;
            $this->info('Super Admin');
        } elseif ($role == 'Moderator') {
            $role_id = 2;
            $this->info('Moderator');
        } else {
            $role_id = 3;
            $this->info('User');
        }
    
        // Ask for email with validation
        $email = $this->ask('What is the email?');
        while (empty($email)) {
            $this->error('Email cannot be empty. Please provide a valid email.');
            $email = $this->ask('What is the email?');
        }
    
        // Ask for password with validation
        $password = $this->secret('Enter a password');
        while (empty($password)) {
            $this->error('Password cannot be empty. Please provide a valid password.');
            $password = $this->secret('Enter a password');
        }

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role_id' => $role_id,
            'wallet_id' => null,
        ]);

        $this->info("User '{$name}' has been successfully created!");
    }
    
}
