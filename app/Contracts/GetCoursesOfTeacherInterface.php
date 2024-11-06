<?php

namespace App\Contracts;

interface GetCoursesOfTeacherInterface {
    public function getCoursesOfTeacher(int $perPage);
}