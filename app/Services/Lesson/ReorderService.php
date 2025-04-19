<?php

namespace App\Services\Lesson;

use App\Models\Lesson;
use App\Services\Base\Service;
use Illuminate\Support\Facades\Log;

class ReorderService extends Service
{
    public function reorder($lesson_ids)
    {
        $lessons = [];
        foreach ($lesson_ids as $position => $id) {
            $lesson = Lesson::find($id);
            $lesson->update(['position' => $position]);
            array_push($lessons, $lesson);
        }

        return $lessons;
    }
}
