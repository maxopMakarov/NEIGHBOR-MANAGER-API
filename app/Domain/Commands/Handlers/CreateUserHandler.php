<?php

namespace App\Domain\Commands\Handlers;

use App\Domain\Commands\CreateUserCommand;
use App\Domain\Commands\Response\CreateUserResponse;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Models\User;

class CreateUserHandler
{
    protected $user;

    public function __construct(UserRepositoryInterface $user)
    {
        $this->user = $user;
    }
    public function __invoke(CreateUserCommand $command)
    {
        return new CreateUserResponse($this->user->create([
            'name' => $command->name,
            'email' => $command->email,
            'password' => $command->password,
        ]))->getData();
    }
}