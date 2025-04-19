<?php

namespace codetyme\TempPassword\Console;

use Illuminate\Console\Command;
use TempPass;

class GenerateTempPassCommand extends Command
{
    // No need to make email and model part of the signature as required
    protected $signature = 'temp-password:generate 
                            {--email= : User email address (required)} 
                            {--model= : The user auth model (optional, defaults to App\Models\User)}
                            {--length= : Password length (optional, defaults to config value)}
                            {--strength= : Password strength (optional, defaults to config value)}';

    protected $description = 'Generate a one-time temporary password for a user';

    public function handle()
    {
        // Prompt for email if not provided
        $email = $this->option('email') ?? null;
        if (! $email) {
            $email = $this->ask('Please enter the user\'s email');
        }

        // Use default model if not provided
        $modelClass = $this->option('model') ?: '\\App\\Models\\User';

        // Use default length if not provided
        $length = $this->option('length') ?? null;

        // Use default strength if not provided
        $strength = $this->option('strength') ?? null;

        if (! class_exists($modelClass)) {
            $this->error("🚫 Model class {$modelClass} does not exist.");
            return Command::FAILURE;
        }

        if (! is_subclass_of($modelClass, '\\Illuminate\\Database\\Eloquent\\Model')) {
            $this->error("🚫 Model class {$modelClass} is not a valid Eloquent model.");
            return Command::FAILURE;
        }

        $model = $modelClass::where('email', $email)->first();

        if (! $model) {
            $this->error("🚫 No {$modelClass} found with email {$email}.");
            return Command::FAILURE;
        }

        $tempPassword = TempPass::generate($model, $length, $strength);

        $this->info("✅ Temporary password generated for {$modelClass} (email: {$email}):");
        $this->line("🔐 Password: <fg=green>{$tempPassword}</>");
        $this->line("⏳ Valid for ".config('temp-password.expiry_minutes', 5)." minutes and one-time use only.");
        
        return Command::SUCCESS;
    }
}
