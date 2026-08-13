<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Represents the permissions table through Eloquent.
 *
 * Maps to the persistence layer and provides ORM capabilities via Illuminate Database.
 */
final class EloquentPermissionModel extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * The table associated with the model.
     *
     * @var string The table name used by Eloquent.
     */
    protected $table = 'permissions';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool Whether Eloquent maintains timestamp columns.
     */
    public $timestamps = true;
}
