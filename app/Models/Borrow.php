<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Borrow extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'borrow_date',
        'due_date',
        'return_date',
        'uuid',
    ];

    /**
     * Generate UUID.
     *
     * @return uuid
     */
    public static function boot()
    {
        parent::boot();
        static::creating(
            function ($model) {
                $model->uuid = (string) Str::uuid();
            }
        );
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function book(){
        return $this->belongsTo(Book::class, 'book_id', 'id');
    }
}
