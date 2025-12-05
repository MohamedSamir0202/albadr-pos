<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\ClientRegistrationEnum;
use App\Enums\ClientStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\LoginRequest;
use App\Http\Requests\Api\V1\SignupRequest;
use App\Http\Resources\V1\ClientResource;
use App\Models\Client;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponse;
    public function login(LoginRequest $request)
    {
        $client = Client::where('email', $request->email)->first();
        if (!$client || !password_verify($request->password, $client->password)) {
            return $this->apiErrorMessage("Invalid credentials", 401);
        }
//        if (!auth('api')->validate($request->validated())){
//            return $this->apiErrorMessage("Invalid credentials", 401);
//        }
//        $client = Client::where('email', $request->email)->first();
        $token = $client->createToken("api_token")->plainTextToken;
        return $this->responseApi([
            'token' => $token,
            'client' => new ClientResource($client)
        ], "Client logged in successfully");

    }

    public function signup(SignupRequest $request)
    {
        $data = $request->validated();
        $data += [
            'password' => bcrypt($data['password']),
            'status' => ClientStatusEnum::active->value,
            'registered_via' => ClientRegistrationEnum::app->value,
        ];
        $client = Client::create($data);
        $token = $client->createToken("api_token")->plainTextToken;
        return $this->responseApi([
            'token' => $token,
            'client' => new ClientResource($client)
        ], "Client registered successfully", 201);
    }
}
