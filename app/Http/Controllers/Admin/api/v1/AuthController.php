<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\ClientRegistrationEnum;
use App\Enums\ClientStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\LoginRequest;
use App\Http\Requests\Api\V1\ProfileRequest;
use App\Http\Requests\Api\V1\SignupRequest;
use App\Http\Resources\V1\ClientResource;
use App\Models\Client;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponse;
    public function login(LoginRequest $request)
    {
        $client = Client::where('email', $request->email)->first();
        if (!$client || !Hash::check($request->password, $client->password)) {
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
        $client = Client::create($data);
        $client->password = bcrypt($data['password']);
        $client->status = ClientStatusEnum::active->value;
        $client->registered_via = ClientRegistrationEnum::app->value;
        $client->save();

        $token = $client->createToken("api_token")->plainTextToken;
        return $this->responseApi([
            'token' => $token,
            'client' => new ClientResource($client)
        ], "Client registered successfully", 201);
    }

    public function getProfile(Request $request)
    {
        $client = $request->user();
        return $this->responseApi(new ClientResource($client), "Client profile retrieved successfully");
    }

    public function updateProfile(ProfileRequest $request)
    {
        $client = auth('api')->user();
        $data = $request->validated();
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        $client->update($data);
        return $this->responseApi(new ClientResource($client), "Client profile updated successfully");
    }
}
