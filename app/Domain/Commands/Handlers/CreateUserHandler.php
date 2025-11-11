<?php

namespace App\Domain\Commands\Handlers;

use App\Domain\Commands\CreateUserCommand;
use App\Domain\Commands\Response\CreateUserResponse;
use App\Domain\Repositories\UserRepositoryInterface;
use Illuminate\Support\Str;
use kornrunner\Keccak;
use Elliptic\EC;

class CreateUserHandler
{
    protected $user;

    public function __construct(UserRepositoryInterface $user)
    {
        $this->user = $user;
    }
    public function __invoke(CreateUserCommand $command)
    {  
        if ($command->eth_address) {
            if (!$this->verifySignature($command->message, $command->signature, $command->eth_address)) {
                throw new \Exception('Invalid signature for the provided Ethereum address.');
            }
        }

        return new CreateUserResponse($this->user->create([
            'name' => $command->name,
            'email' => $command->email,
            'password' => $command->password,
            'eth_address' => $command->eth_address,
        ]))->getData();
    }

    protected function verifySignature($message, $signature, $address): bool
    {
        $messageLength = strlen($message);
        $hash = Keccak::hash("\x19Ethereum Signed Message:\n{$messageLength}{$message}", 256);
        $sign = [
            "r" => substr($signature, 2, 64),
            "s" => substr($signature, 66, 64)
        ];

        $recId  = ord(hex2bin(substr($signature, 130, 2))) - 27;

        if ($recId != ($recId & 1)) {
            return false;
        }

        $publicKey = (new EC('secp256k1'))->recoverPubKey($hash, $sign, $recId);

        return $this->pubKeyToAddress($publicKey) === Str::lower($address);
    }

    protected function pubKeyToAddress($publicKey): string
    {
        return "0x" . substr(Keccak::hash(substr(hex2bin($publicKey->encode("hex")), 1), 256), 24);
    }
}