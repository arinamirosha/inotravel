<?php

namespace App\Console\Commands;

use App\TemporaryImage;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class CleanImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:cleanImages {--from=} {--to=} {--user_id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete unused images';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $from = $this->option('from');
        $to = $this->option('to');
        $userId = $this->option('user_id');

        if (( ! $from && $to && $userId) ||
            ($from && ! $to && $userId) ||
            ($from && ! ($to || $userId)) ||
            ($to && ! ($from || $userId))) {
            echo 'Wrong parameters';

            return 0;
        }

        // Сделать валидацию

        $data = [];
        if ($from || $to || $userId) {
            $data['from'] = $from;
            $data['to'] = $to;
            $data['userId'] = $userId;
        }

        \App\Jobs\CleanImages::dispatch($data);

        return 0;
    }
}
