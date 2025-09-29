<?php

namespace App\Domain\Commands\Handlers;

use App\Domain\Commands\CreateUserCommand;
use App\Domain\Commands\Response\CreateUserResponse;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Models\User;

class GetUserByIdHandler
{
    protected $user;

    public function __construct(UserRepositoryInterface $user)
    {
        $this->user = $user;
    }
    public function __invoke(CreateUserCommand $command)
    {
        $user = $this->user->create([
            'name' => $command->name,
            'email' => $command->email,
            'password' => $command->password,
        ]);
        return new CreateUserResponse($user)->getData();
    }
}