<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Represents the roles table through Eloquent.
 *
 * Maps to the persistence layer and provides ORM capabilities via Illuminate Database.
 */
final class EloquentRoleModel extends Model
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
    protected $table = 'roles';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool Whether Eloquent maintains timestamp columns.
     */
    public $timestamps = true;

    /**
     * Returns the permissions granted to this role.
     *
     * @return BelongsToMany The permissions relation.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(EloquentPermissionModel::class, 'roles_permissions', 'role_id', 'permission_id');
    }
}
