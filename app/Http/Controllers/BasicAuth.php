<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BasicAuth extends Controller
{
    public function authenticate(Request $request)
    {
        $username = $request->header('php-auth-user');
        $password = $request->header('php-auth-pw');

        $response = Http::asForm()->post('http://127.0.0.1/oauth/token', [
            'grant_type' => 'password',
            'client_id' => '1',
            'client_secret' => '6aUHUy1LK86dbtmDGwkelpychpELmyqo18ZXW4KH',
            'username' => "{$username}@basicauth",
            'password' => $password,
            'scope' => '',
        ]);

        dd($response);
        return $response->json();
    }
}
