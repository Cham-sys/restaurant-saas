<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ResetUserPasswordCommand extends Command
{
    protected $signature = 'user:reset-password {email : The user email address}';

    protected $description = 'Reset a user password for local administration';

    public function handle(): int
    {
        $user = User::where('email', $this->argument('email'))->first();

        if (! $user) {
            $this->error('No user was found with this email.');

            return self::FAILURE;
        }

        $password = $this->secret('New password');

        if (blank($password)) {
            $this->error('The password cannot be empty.');

            return self::FAILURE;
        }

        $user->forceFill(['password' => Hash::make($password)])->save();

        $this->info("Password reset successfully for {$user->email}.");

        return self::SUCCESS;
    }
}
