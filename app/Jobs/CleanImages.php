<?php

namespace App\Jobs;

use App\TemporaryImage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CleanImages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $data;

    /**
     * Create a new job instance.
     *
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = $this->data;

        if ( ! $data) {
            Log::info('no data');
            $imagesToDelete = TemporaryImage::where('created_at', '<', Carbon::now()->subDay())->get();

        } else {
            $from = isset($data['from']) ? $data['from'] : null;
            $to = isset($data['to']) ? $data['to'] : null;
            $userId = isset($data['userId']) ? $data['userId'] : null;

            if ($from && $to && ! $userId) {
                Log::info('only from and to');
                $imagesToDelete = TemporaryImage::whereBetween('created_at', [$from, $to])->get();

            } elseif ($from && $to && $userId) {
                Log::info('from, to, user_id');
                $imagesToDelete = TemporaryImage::whereBetween('created_at', [$from, $to])
                    ->where('user_id', '=', $userId)->get();

            } elseif ( ! ($from || $to) && $userId) {
                Log::info('only user_id');
                $imagesToDelete = TemporaryImage::where('user_id', '=', $userId)->get();
            }
        }

        foreach ($imagesToDelete as $img) {
            Storage::disk('public')->delete($img->image);
            $img->delete();
        }
    }
}
