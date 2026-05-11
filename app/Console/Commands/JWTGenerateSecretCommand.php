<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class JWTGenerateSecretCommand extends Command
{
    protected $signature = 'jwt:secret {--f|force : Overwrite existing secret without confirmation}';

    protected $description = 'Set the JWT secret key used to sign tokens';

    public function handle(): int
    {
        $path = $this->laravel->environmentFilePath();

        if (! file_exists($path)) {
            $this->error('.env file does not exist.');

            return self::FAILURE;
        }

        $contents = file_get_contents($path);
        $key = Str::random(64);

        if (preg_match('/^JWT_SECRET=/m', $contents)) {
            if (! $this->isConfirmed()) {
                $this->comment('No changes were made to your secret key.');

                return self::SUCCESS;
            }

            file_put_contents($path, preg_replace('/^JWT_SECRET=.*/m', "JWT_SECRET=$key", $contents));
        } else {
            file_put_contents($path, $contents . PHP_EOL . "JWT_SECRET=$key" . PHP_EOL);
        }

        $this->laravel['config']['jwt.secret'] = $key;
        $this->info("JWT secret [$key] set successfully.");

        return self::SUCCESS;
    }

    private function isConfirmed(): bool
    {
        return $this->option('force') || $this->confirm(
            'This will invalidate all existing tokens. Are you sure you want to override the secret key?'
        );
    }
}
