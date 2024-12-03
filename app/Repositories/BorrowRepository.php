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

    /**
     * Retrieve the number of active users on a weekly basis.
     *
     * @return array The response array containing the weekly active users data.
     */
    public function weeklyActiveUsers(){
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $weeklyActiveBorrowers = DB::table('borrows')
            ->select(
                DB::raw('EXTRACT(week FROM borrow_date) as week'),
                DB::raw('COUNT(DISTINCT user_id) as active_borrowers')
            )
            ->whereYear('borrow_date', $currentYear)
            ->whereMonth('borrow_date', $currentMonth)
            ->groupBy(DB::raw('EXTRACT(week FROM borrow_date)'))
            ->orderBy('week')
            ->get();

        $weeklyData = [];
        $i=1;
        foreach ($weeklyActiveBorrowers as $weekData) {
            $weekNumber = $i++;
            $weeklyData[$weekNumber] = $weekData->active_borrowers;
        }
        return $weeklyData;
    }



    /**
     * Retrieves the books that have been borrowed for the longest time.
     *
     * @return \Illuminate\Support\Collection
     */
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
