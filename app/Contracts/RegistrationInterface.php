<?php

namespace App\Contracts;

interface RegistrationInterface
{
    public function registerViaEmail(array $data);
    // public function registerViaSocialite($provider, $userData);
}
