<?php
namespace codetyme\TempPassword;

use codetyme\TempPassword\Models\TempPassword;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Str;

class TempPass
{
    public function generate(Authenticatable $model, int $length = null, string $strength = null): string
    {
        // Use custom length if provided, fallback to config (default: 8)
        $length = $length ?? config('temp-password.length', 8);

        // Use custom strength if provided, fallback to config (default: 'medium')
        // Options: simple, medium, strong
        // 'simple' - only lowercase letters
        // 'medium' - letters (upper & lower) + numbers
        // 'strong' - lowercase letters, numbers, and special characters
        $strength = $strength ?? config('temp-password.strength', 'medium');

        // Generate a random plain-text password
        $plain = $this->generatePassword($length, $strength);

        // Store the hashed password linked to the model
        TempPassword::create([
            'authenticatable_id' => $model->getAuthIdentifier(),
            'authenticatable_type' => get_class($model),
            'temp_password' => bcrypt($plain),
        ]);

        // Return the plain password (to show or send to the user)
        return $plain;
    }

    public function validate(int $modelId, string $password, string $modelClass): bool
    {
        // Check if the password is valid and not used
        $record = TempPassword::where([
            'authenticatable_id' => $modelId,
            'authenticatable_type' => $modelClass,
            'used' => false,
        ])->latest()->first();

        // Check if the password matches and is not expired
        if ($record && password_verify($password, $record->temp_password)) {
            if (now()->diffInMinutes($record->created_at) <= 5) {
                $record->used = true;
                $record->save();
                return true;
            }
        }

        // If the password is invalid or expired, return false
        return false;
    }

    protected function generatePassword(int $length, string $strength): string
    {
        $lower = 'abcdefghijklmnopqrstuvwxyz';
        $upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';
        $symbols = '!@#$%^&*()-_=+[]{}|;:,.<>?';

        // Determine the character pool based on the strength
        switch (strtolower($strength)) {
            case 'simple':
                $pool = $lower;
                break;

            case 'medium':
                $pool = $lower . $upper . $numbers;
                break;

            case 'strong':
            default:
                $pool = $lower . $upper . $numbers . $symbols;
                break;
        }

        // Shuffle the pool and take a random sample of the specified length
        return collect(str_split(str_shuffle($pool)))
            ->random($length)
            ->implode('');
    }
}
