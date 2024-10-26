<?php

namespace App\Contracts;

interface LoginInterface
{
    public function loginViaEmail(array $data);
    // public function loginViaSocialite(string $provider, array $userData);
}
