<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\User;

use Illuminate\Database\Eloquent\Model;

/**
 * Represents the users table through Eloquent.
 *
 * Maps to the persistence layer and provides ORM capabilities via Illuminate Database.
 */
final class EloquentUserModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string The table name used by Eloquent.
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
     * @var bool Whether Eloquent maintains timestamp columns.
     */
    public $timestamps = true;
}
