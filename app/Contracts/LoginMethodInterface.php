<?php

namespace App\Contracts;

interface LoginMethodInterface
{
    public function loginViaEmail(array $data);
}
