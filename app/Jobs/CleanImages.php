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
     * Execute the job. Remove unnecessary images.
     *
     * @return void
     */
    public function handle()
    {
        $data = $this->data;

        if (!$data)
        {
            // no parameters, get images uploaded more than 1 day ago
            $imagesToDelete = TemporaryImage::where('created_at', '<', Carbon::now()->subDay())->get();

        }
        else
        {
            $from   = isset($data['from']) ? $data['from'] : null;
            $to     = isset($data['to']) ? $data['to'] : null;
            $userId = isset($data['userId']) ? $data['userId'] : null;

            if ($from && $to && !$userId)
            {
                // only date range is set, get images uploaded between these dates
                $imagesToDelete = TemporaryImage::whereBetween('created_at', [$from, $to])->get();
            }
            elseif ($from && $to && $userId)
            {
                // date range and user id are specified, get images uploaded between these dates for that user
                $imagesToDelete = TemporaryImage::whereBetween('created_at', [$from, $to])
                                                ->where('user_id', '=', $userId)->get();
            }
            elseif (!($from || $to) && $userId)
            {
                // only user id is set, get images uploaded by this user
                $imagesToDelete = TemporaryImage::where('user_id', '=', $userId)->get();
            }
        }

        foreach ($imagesToDelete as $img)
        {
            Storage::disk('public')->delete($img->image);
            $img->delete();
        }
    }
}
