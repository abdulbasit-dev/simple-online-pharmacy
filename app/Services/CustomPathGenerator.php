<?php

namespace App\Services;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class CustomPathGenerator implements PathGenerator
{
    public function getPath(Media $media): string
    {
        if (config("app.media_disk") == 's3') {
            if (config('app.env') == 'production') {
                return 'uploads/' . strtolower(substr(strrchr($media->model_type, "\'"), 1)) . 's' . '/' . $media->id . '/';
            } elseif (config('app.env') == 'dev') {
                return 'dev_uploads/' . strtolower(substr(strrchr($media->model_type, "\'"), 1)) . 's' . '/' . $media->id . '/';
            } else {
                return 'local_uploads/' . strtolower(substr(strrchr($media->model_type, "\'"), 1)) . 's' . '/' . $media->id . '/';
            }
        }else{
            return 'uploads/' . strtolower(substr(strrchr($media->model_type, "\'"), 1)) . 's' . '/' . $media->id . '/';
        }
    }

    public function getPathForConversions(Media $media): string
    {
        return $this->getPath($media) . 'conversions/';
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getPath($media) . 'responsive/';
    }
}
