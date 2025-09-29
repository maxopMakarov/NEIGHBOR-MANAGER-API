<?php

namespace App\Domain\Commands\Response;

use App\Models\User;

class GetUserByIdResponse
{
    protected $data;

    public function __construct($user)
    {
        $this->data = $user;
    }
    public function getData(): User
    {
        return $this->data;
    }
}