<?php

namespace App\Jobs;

use App\Models\Comments;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
        try {
            Redis::ping();

            $uniqueKey = 'comment_2025_{$id_from_database}_{$primary_key_database_if_exist}';

            $getData = Redis::get($uniqueKey);

            if (!$getData) {
                $comment = Comments::find($this->commentId);

                if ($comment) {
                    $comment = Comments::find($this->commentId);
                    Redis::set($uniqueKey);

                    $comment->save();

                    Log::info("✅ Comment {$this->commentId} processed by Redis queue.");
                } else {
                    Log::error("❌ Comment ID: {$this->commentId} not found in the database.");
                }
            } else {
                Log::info("✅ Comment {$this->commentId} retrieved from Redis cache.");
            }
        } catch (\Exception $e) {
            Log::error("❌ Redis connection failed: " . $e->getMessage());
        }
    }

}
