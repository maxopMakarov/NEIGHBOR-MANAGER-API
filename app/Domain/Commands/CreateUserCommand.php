<?php

namespace App\Domain\Commands;

class CreateUserCommand
{
    public string $name;
    public string $email;
    public ?string $password;
    public ?string $eth_address;
    public ?string $message;
    public ?string $signature;

    public function __construct(
        string $name,
        string $email, 
        ?string $password, 
        ?string $eth_address = null, 
        ?string $message = null, 
        ?string $signature = null
    )
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->eth_address = $eth_address;
        $this->message = $message;
        $this->signature = $signature;
    }
}