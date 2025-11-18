<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class MidtransClient
{
    public function status(string $transactionId): array
    {
        $serverKey = config('midtrans.server_key');

        if (! $serverKey) {
            throw new RuntimeException('MIDTRANS_SERVER_KEY belum dikonfigurasi.');
        }

        return Http::withHeaders([
            'Authorization' => 'Basic '.base64_encode($serverKey.':'),
            'Accept' => 'application/json',
        ])->get(config('midtrans.base_url').'/v2/'.$transactionId.'/status')
            ->throw()
            ->json();
    }
}
