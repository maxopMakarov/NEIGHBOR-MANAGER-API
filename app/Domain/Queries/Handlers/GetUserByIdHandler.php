<?php

namespace App\Domain\Queries\Handlers;

use App\Domain\Queries\GetUserByIdQuery;
use App\Domain\Queries\Response\GetUserByIdResponse;
use App\Domain\Repositories\UserRepositoryInterface;
use Illuminate\Support\Str;
use kornrunner\Keccak;
use Elliptic\EC;

class GetUserByIdHandler
{
    protected $user;

    public function __construct(UserRepositoryInterface $user)
    {
        $this->user = $user;
    }
    public function __invoke(GetUserByIdQuery $query)
    {
        if ($query->eth_address) {
            if (!$this->verifySignature($query->message, $query->signature, $query->eth_address)) {
                throw new \Exception('Invalid signature for the provided Ethereum address.');
            }
        }

        $user = $this->user->findByEthAddress($query->eth_address);
        return new GetUserByIdResponse($user)->getData();
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