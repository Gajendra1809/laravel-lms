<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\StatusEnum;

class Book extends Model
{
    use HasFactory, SoftDeletes;

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

    public function getAvailableAttribute($value){
        if($value == StatusEnum::AVAILABLE->value){
            return StatusEnum::AVAILABLE->label();
        }elseif($value == StatusEnum::NOTAVAILABLE->value){
            return StatusEnum::NOTAVAILABLE->label();
        }
    }

    public static function search($query)
    {
        return self::whereRaw("to_tsvector('english', title || ' ' || author || ' ' || isbn || ' ' || available) @@ plainto_tsquery('english', ?)", [$query])
            ->get();
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id', 'id');
    }

}
