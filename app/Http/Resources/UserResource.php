<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

    protected $showSensitiveFields = false;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if (!$this->showSensitiveFields) {
            $this->resource->addHidden(['phone', 'email']);
        }

        $data = parent::toArray($request);

        $data['bound_phone'] = $this->resource->phone ? true : false;
        $data['bound_wechat'] = ($this->resource->wx_openid || $this->resource->wx_unionid) ?true : false;

        return $data;
    }

    public function showSensitiveFields()
    {
        $this->showSensitiveFields = true;
        return $this;
    }
}
