<?php

namespace App\Domain\Commands\Response;

use App\Models\User;

class CreateUserResponse
{
    protected $data;

    public function __construct(User $user)
    {
        $this->data = $user;
    }
    public function getData()
    {
        return $this->data;
    }
}