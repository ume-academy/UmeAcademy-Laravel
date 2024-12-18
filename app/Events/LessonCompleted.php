<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LessonCompleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $courseId;
    public $lessonId;
    public $userId;
    public $progress;
    public $totalLessonCompleted;

    /**
     * Create a new event instance.
     */
    public function __construct($courseId, $lessonId, $userId, $progress, $totalLessonCompleted)
    {
        $this->courseId = $courseId;
        $this->lessonId = $lessonId;
        $this->userId = $userId;
        $this->progress = $progress;
        $this->totalLessonCompleted = $totalLessonCompleted;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): Channel
    {
        return new PrivateChannel('course.' . $this->courseId . 'user.' . $this->userId);
    }

    public function broadcastAs()
    {
        return 'LessonCompleted';
    }
}
