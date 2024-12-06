<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;

class PDFService
{
    public function createCertificate($course, $user) {
        $lessons = $course->lessons;

            $latestCompletionDate = $user->lessons()
                ->whereIn('lessons.id', $lessons->pluck('id'))
                ->latest('lesson_completeds.created_at')
                ->value('lesson_completeds.created_at');

            if ($latestCompletionDate) {
                $completionDate = \Carbon\Carbon::parse($latestCompletionDate);
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
            
            // Tạo PDF
            $pdf = Pdf::loadView('certificate', $data)
                ->setOption('isHtml5ParserEnabled', true)
                ->setOption('isPhpEnabled', true)
                ->setOption('isJavascriptEnabled', true)
                ->setPaper('A4', 'landscape');

            return $pdf->stream('certificate.pdf');
    }
}
