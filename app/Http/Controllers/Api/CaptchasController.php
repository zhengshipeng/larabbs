<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\CaptchaRequest;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CaptchasController extends Controller
{

    /**
     * @param CaptchaRequest $captchaRequest
     * @param CaptchaBuilder $captchaBuilder
     * @return $this
     */
    public function store(CaptchaRequest $captchaRequest, CaptchaBuilder $captchaBuilder)
    {
        $key = "captcha_" . Str::random(15);
        $phone = $captchaRequest->phone;

        $captcha = $captchaBuilder->build();
        $expiredAt = now()->addMinutes(2);

        \Cache::put($key, ['phone' => $phone, 'code' => $captcha->getPhrase()], $expiredAt);

        $result = [
            'captcha_key' => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
            'captcha_image_content' => $captcha->inline(),
        ];

        return response()->json($result)->setStatusCode(201);
    }
}
