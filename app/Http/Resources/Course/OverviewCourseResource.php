<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OverviewCourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id ?? null,
            'name' => $this->name ?? null,
            'summary' => $this->summary ?? null,
            'description' => $this->description ?? null,
            'course_learning_benefit' => $this->courseBenefits(),
            'course_requirement' => $this->courseRequirements()
        ];
    }

    private function courseBenefits() {
        return $this->courseBenefits->map(function ($benefit) {
            return [
                'id' => $benefit->id,
                'name' => $benefit->name
            ];
        })->toArray();
    }

    private function courseRequirements() {
        return $this->courseRequirements->map(function ($requirement) {
            return [
                'id' => $requirement->id,
                'name' => $requirement->name
            ];
        })->toArray();
    }
}
