<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\User;

use Illuminate\Database\Eloquent\Model;

/**
 * Eloquent model representing the users table.
 *
 * Maps to the persistence layer and provides ORM capabilities via Illuminate Database.
 */
final class UserModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
}
