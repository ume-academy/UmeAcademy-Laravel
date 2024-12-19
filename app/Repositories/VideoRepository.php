<?php

namespace App\Repositories;

use App\Models\Video;
use App\Repositories\Interfaces\VideoRepositoryInterface;

class VideoRepository implements VideoRepositoryInterface
{
    public function create(array $data)
    {
        return Video::create($data);
    }

    public function updateVideo(int $id, bool $preview) {
        $video = Video::findOrFail($id);
        $video->is_preview = $preview;
        $video->save();

        return $video;
    }

    public function deleteVideo(int $id) {
        $video = Video::findOrFail($id);

        return $video->delete();
    }
}
