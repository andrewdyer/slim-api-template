<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

/**
 * Represents the users table through Eloquent.
 *
 * Maps to the persistence layer and provides ORM capabilities via Illuminate Database.
 */
final class EloquentUserModel extends Model
{
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
     * The table associated with the model.
     *
     * @var string The table name used by Eloquent.
     */
    protected $table = 'users';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool Whether Eloquent maintains timestamp columns.
     */
    public $timestamps = true;

    /**
     * Returns the distinct permissions granted to the user through its assigned roles.
     *
     * Permissions reach the user through two pivot tables (users_roles, then
     * roles_permissions), so this cannot be a genuine BelongsToMany relation
     * (Eloquent requires a single pivot table linking both sides). It is
     * derived from the roles() relation instead.
     *
     * @return Collection<int, EloquentPermissionModel> The user's distinct permissions.
     */
    public function permissions(): Collection
    {
        return $this->roles()
            ->with('permissions')
            ->get()
            ->flatMap(fn (EloquentRoleModel $role): Collection => $role->permissions)
            ->unique('id')
            ->values();
    }

    /**
     * Returns the roles assigned to the user.
     *
     * @return BelongsToMany The roles relation.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(EloquentRoleModel::class, 'users_roles', 'user_id', 'role_id');
    }
}
