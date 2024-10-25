<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\StatusEnum;
use Laravel\Scout\Searchable;

class Book extends Model
{
    use HasFactory, SoftDeletes, Searchable;

    protected $table = 'books';

    protected $fillable = [
        'title',
        'author',
        'isbn',
        'published_date',
        'available',
        'uuid',
        'admin_id',
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

    /**
     * Return the available status as a string.
     *
     * @param int $value
     * @return string
     */
    public function getAvailableAttribute($value){
        if($value == StatusEnum::AVAILABLE->value){
            return StatusEnum::AVAILABLE->label();
        }elseif($value == StatusEnum::NOTAVAILABLE->value){
            return StatusEnum::NOTAVAILABLE->label();
        }
    }

     /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'title' => $this->title,
            'author' => $this->author,
            'isbn' => $this->isbn,
            'available' => $this->available
        ];
    }

    /**
     * Returns the admin who created the book.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id', 'id');
    }

}
