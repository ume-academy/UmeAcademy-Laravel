<?php

namespace App\Services;

use App\Models\Certificate;
use App\Traits\HandleFileTrait;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class PDFService
{
    use HandleFileTrait;

    public function createCertificate($course, $user) {
        $data = [
            'user_id' => $user->id,
            'course_id' => $course->id
        ];

        $certificate = Certificate::create($data);

        $certificate = Certificate::where('user_id', $data['user_id'])
        ->where('course_id', $data['course_id'])
        ->first();

        $lessons = $course->lessons;

        $latestCompletionDate = $user->lessons()
            ->whereIn('lessons.id', $lessons->pluck('id'))
            ->latest('lesson_completeds.created_at')
            ->value('lesson_completeds.created_at');

        if ($latestCompletionDate) {
            $completionDate = Carbon::parse($latestCompletionDate);
            $day = $completionDate->day;
            $month = $completionDate->month;
            $year = $completionDate->year;
        } else {
            $day = $month = $year = null;
        }

        $totalSeconds = $course->duration;
        $minutes = floor($totalSeconds / 60);
        $seconds = $totalSeconds % 60;

        $data = [
            'name' => $user->fullname,
            'course' => $course->name,
            'teacher' => $course->teacher->user->fullname,
            'day' => $day,
            'month' => $month,
            'year' => $year,
            'minutes' => $minutes,
            'seconds' => $seconds,
            'bannerPath' => public_path('certificate/banner.png'),
            'logoPath' => public_path('certificate/logo.png'),
            'scriptPath' => public_path('certificate/css/main.js')
        ];

        $pdf = Pdf::loadView('certificate', $data)
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isPhpEnabled', true)
            ->setOption('isJavascriptEnabled', true)
            ->setPaper('A4', 'landscape');

        $fileName = $certificate->id . '.pdf';

        $filePath = storage_path('app/certificates/' . $fileName);
        Storage::put('certificates/' . $fileName, $pdf->output());
        
        return $fileName;
    }
}
