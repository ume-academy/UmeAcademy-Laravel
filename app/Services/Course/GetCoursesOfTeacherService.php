<?php

namespace App\Services\Course;

use App\Contracts\GetCoursesOfTeacherInterface;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Traits\ValidationTrait;

class GetCoursesOfTeacherService implements GetCoursesOfTeacherInterface
{
    use ValidationTrait;

    public function __construct(
        private CourseRepositoryInterface $courseRepo,
    ) {}

    public function getCoursesOfTeacher($perPage)
    {
        $user = $this->validateTeacher();

        return $this->courseRepo->getByTeacher($user->teacher->id, $perPage);
    }
}
