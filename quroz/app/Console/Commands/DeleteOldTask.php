<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use Carbon\Carbon;
use Log;

class DeleteOldTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Delete:permanantly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete soft deleted records permanantly';

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
        $oneMonthAgo = Carbon::now()->subMonth();

        $tasksToDelete = Task::onlyTrashed()
            ->where('deleted_at', '<', $oneMonthAgo)
            ->get();

        //permanant delete
        foreach ($tasksToDelete as $task) {
            $task->forceDelete();
        }

        \Log::info("saad");
    }
}
