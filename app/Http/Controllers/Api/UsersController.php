<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\UsersRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class UsersController extends Controller
{

    /**
     * @param UsersRequest $userRequest
     * @return UserResource
     * @throws AuthenticationException
     */
    public function store(UsersRequest $userRequest)
    {
        $verifyData = \Cache::get($userRequest->verification_key);

        if (!$verifyData) {
            abort(403, '验证码失败');
        }

        if (!hash_equals($verifyData['code'], $userRequest->verification_code)) {
            throw new AuthenticationException('验证码错误');
        }

        $user = User::create([
            'name' => $userRequest->name,
            'phone' => $verifyData['phone'],
            'password' => $userRequest->password,
        ]);

        \Cache::forget($userRequest->verification_key);

        return new UserResource($user);
    }












}
