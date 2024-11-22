<?php

namespace App\Repositories;

use App\Models\Borrow;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Repository for the Borrow model.
 *
 * @package App\Repositories
 *
 */
class BorrowRepository extends BaseRepository
{
    /**
     * Constructor to bind model to repo
     *
     * @param Borrow $borrow Borrow model
     */
    public function __construct(Borrow $borrow)
    {
        $this->model = $borrow;
    }

    public function weeklyActiveUsers(){
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Query to get active borrowers grouped by week for the current month
        $weeklyActiveBorrowers = DB::table('borrows')
            ->select(
                DB::raw('EXTRACT(week FROM borrow_date) as week'), // Extract the week number from borrow_date
                DB::raw('COUNT(DISTINCT user_id) as active_borrowers') // Count distinct users for each week
            )
            ->whereYear('borrow_date', $currentYear) // Filter by current year
            ->whereMonth('borrow_date', $currentMonth) // Filter by current month
            ->groupBy(DB::raw('EXTRACT(week FROM borrow_date)')) // Group by week number
            ->orderBy('week') // Order by week number
            ->get();

        // Prepare the data in a format suitable for the front-end chart
        $weeklyData = [];
        $i=1;
        foreach ($weeklyActiveBorrowers as $weekData) {
            $weekNumber = $i++;
            $weeklyData[$weekNumber] = $weekData->active_borrowers;
        }
        return $weeklyData;
    }


    public function longestBorrowedBooks(){
        return DB::table('borrows')
        ->join('books', 'borrows.book_id', '=', 'books.id')
        ->select('books.id', 'books.title', 'books.isbn')
        ->selectRaw('AVG(
            CASE
                WHEN borrows.return_date IS NULL THEN EXTRACT(DAY FROM AGE(CURRENT_DATE, borrows.borrow_date))
                ELSE EXTRACT(DAY FROM AGE(borrows.return_date, borrows.borrow_date))
            END
        ) as avg_borrow_duration')
        ->groupBy('books.id', 'books.title', 'books.isbn')
        ->orderByDesc('avg_borrow_duration')
        ->limit(10)
        ->get();

    }

}
