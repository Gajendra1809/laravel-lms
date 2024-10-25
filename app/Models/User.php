<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Str;
use App\Enums\UserRoleEnum;
use Laravel\Scout\Searchable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, Searchable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'uuid'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
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
     * Return the role as string.
     *
     * @param  int  $value
     * @return string
     */
    public function getRoleAttribute($value){
        if($value == UserRoleEnum::SUPERADMIN->value){
            return UserRoleEnum::SUPERADMIN->label();
        }elseif($value == UserRoleEnum::ADMIN->value){
            return UserRoleEnum::ADMIN->label();
        }else{
            return UserRoleEnum::USER->label();
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
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role
        ];
    }

    /**
     * The books that belong to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function books()
    {
        return $this->hasMany(Book::class, 'admin_id', 'id');
    }
    
}
