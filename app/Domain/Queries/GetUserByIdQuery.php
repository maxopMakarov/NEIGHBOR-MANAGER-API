<?php

namespace App\Domain\Queries;

class GetUserByIdQuery
{
    public ?string $id;
    public ?string $eth_address;
    public ?string $message;
    public ?string $signature;

    public function __construct(
        ?string $id = null,
        ?string $eth_address = null,
        ?string $message = null,
        ?string $signature = null
    )
    {
        $this->id = $id;
        $this->eth_address = $eth_address;
        $this->message = $message;
        $this->signature = $signature;
    }
}