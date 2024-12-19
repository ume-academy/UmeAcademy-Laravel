<?php

namespace App\Repositories;

use App\Models\Chapter;
use App\Repositories\Interfaces\ChapterRepositoryInterface;

class ChapterRepository implements ChapterRepositoryInterface
{
    public function create(array $data)
    {
        return Chapter::create($data);
    }

    public function find(int $id) {
        return Chapter::findOrFail($id);
    }

    public function update(int $id, array $data) {
        $chapter = $this->find($id);

        return $chapter->update($data);
    }

    public function delete(int $id) {
        $chapter = $this->find($id);

        return $chapter->delete();
    }
}
