<?php

namespace App\Jobs;

use App\Models\Comments;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;

class ProcessComment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $commentId;

    public function __construct($commentId)
    {
        $this->commentId = $commentId;
    }

    public function handle()
    {
        $comment = Comments::find($this->commentId);

        if ($comment) {
        $comment->save();
            Log::info("✅ Comment {$this->commentId} processed by Redis queue.");
        } else {
            Log::error("❌ Failed to process comment ID: {$this->commentId}");
        }
    }
}
