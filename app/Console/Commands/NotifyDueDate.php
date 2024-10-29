<?php

namespace App\Console\Commands;

use App\Models\Borrow;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use App\Notifications\DueDateReminder;

class NotifyDueDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notify-due-date';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify users that their due date for a specific book is tomorrow.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dueTomorrow = now()->addDay();
        $borrows = Borrow::where('due_date', $dueTomorrow)->with('user', 'book')->get();

        foreach ($borrows as $borrow) {
            $user = $borrow->user;
            Notification::send($user, new DueDateReminder($borrow));
        }

        $this->info('Due date reminder notifications sent successfully.');
    }
}
