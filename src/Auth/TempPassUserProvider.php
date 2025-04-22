<?php
namespace codetyme\TempPassword\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use TempPass;

class TempPassUserProvider extends EloquentUserProvider
{
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        $plain = $credentials['password'];

        // First check if the main password matches
        if (Hash::check($plain, $user->getAuthPassword())) {
            return true;
        }

        // Check if temp passwords are enabled in the config
        if (! config('temp-password.enabled')) {
            return false;
        }

        // Fall back to validating the temporary password
        return TempPass::validate(
            $user->getAuthIdentifier(), 
            $plain, 
            get_class($user)
        );
    }
}
