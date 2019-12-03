<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\UsersRequest;
use App\Http\Resources\UserResource;
use App\Models\Image;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class UsersController extends Controller
{

    /**
     * 手机号验证码注册
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

        return (new UserResource($user))->showSensitiveFields();
    }

    /**
     * 获取用户的信息
     * @param User $user
     * @param Request $request
     * @return UserResource
     */
    public function show(User $user, Request $request)
    {
        return new UserResource($user);
    }

    /**
     * 获取当前用户的信息
     * @param Request $request
     * @return UserResource
     */
    public function me(Request $request)
    {
        return (new UserResource($request->user()))->showSensitiveFields();
    }

    /**
     * 修改用户信息
     * @param UsersRequest $usersRequest
     * @return $this
     */
    public function update(UsersRequest $usersRequest)
    {
        $user = $usersRequest->user();

        $attributes = $usersRequest->only(['name', 'email', 'introduction']);

        if ($usersRequest->avatar_image_id) {
            $image = Image::find($usersRequest->avatar_image_id);
            $attributes['avatar'] = $image->path;
        }

        $user->update($attributes);

        return (new UserResource($user))->showSensitiveFields();
    }












}
