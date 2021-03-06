<?php

namespace App\Console\Commands;

use App\TemporaryImage;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Throwable;

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
     * Execute the console command. Check parameters and dispatch CleanImages job
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
            echo __('Wrong parameters');

            return false;
        }

        $data = [];
        if ($from || $to || $userId) {
            $data['from'] = $from;
            $data['to'] = $to;
            $data['userId'] = $userId;
        }

        $messages = App::isLocale('ru') ? [
            'from.date' => __('messages.wrong_date', ['name' => 'from']),
            'to.date' => __('messages.wrong_date', ['name' => 'to']),
            'userId.numeric' => __('messages.id_not_number'),
        ] : [];

        $validator = Validator::make($data, [
            'from' => ['date', 'nullable'],
            'to' => ['date', 'nullable'],
            'userId' => ['numeric', 'nullable'],
        ], $messages);

        if ($validator->fails()) {
            $messages = $validator->errors()->getMessages();
            foreach ($messages as $message) {
                echo $message[0]."\n";
            }

            return false;
        }

        \App\Jobs\CleanImages::dispatch($data);

        return false;
    }
}
