<?php

namespace App\Console\Commands;

use App\Models\Borrow;
use Illuminate\Console\Command;
use App\Notifications\OverdueBookLateFee;
use Illuminate\Support\Facades\Notification;

class NotifyOverdueBooks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notify-overdue-books';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify overdue books with late fee payment link';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $borrows = Borrow::where([
                        ['due_date', '<', now()],
                        ['return_date', null],
                        ['late_fee', 0],
                    ])->with(['user', 'book'])->get();
        
        foreach ($borrows as $borrow) {
            $borrow->update(['late_fee' => config('settings.set.latefee')]);
            Notification::send($borrow->user, new OverdueBookLateFee($borrow));
        }
        $this->info('Overdue book late fee notifications sent successfully.');
    }
}
