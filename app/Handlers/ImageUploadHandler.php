<?php

namespace App\Handlers;

use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ImageUploadHandler
{
    protected $allowed_at = ["png", "jpg", "gif", "jpeg"];

    /**
     * 图片上传处理
     * @param $file
     * @param $folder
     * @param $file_prefix
     * @return array|bool
     */
    public function save($file, $folder, $file_prefix, $max_width = false)
    {
        $folder_name = "uploads/images/$folder/" .  date("Ym/d", time());
        $upload_path = public_path() . '/' . $folder_name;
        $extension = strtolower($file->getClientOriginalExtension()) ?: "png";
        $file_name = $file_prefix . '_' . time() . '_' . Str::random(10) . '.' . $extension;
        if (!in_array($extension, $this->allowed_at)) {
            return false;
        }
        $file->move($upload_path, $file_name);
        if ($max_width && $extension != 'gif') {
            $this->reduceSize($upload_path . '/' . $file_name, $max_width);
        }
        return [
            'path' => config('app.url') . "/$folder_name/$file_name"
        ];
    }

    /**
     * 裁剪上传的图片
     * @param $file_path
     * @param $max_width
     */
    public function reduceSize($file_path, $max_width)
    {
        $image = Image::make($file_path);
        $image->resize($max_width, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $image->save();
    }
}