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

        if (Hash::check($plain, $user->getAuthPassword())) {
            return true;
        }

        return TempPass::validate(
            $user->getAuthIdentifier(), 
            $plain, 
            get_class($user)
        );
    }
}
