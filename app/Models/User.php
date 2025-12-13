<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;  // Import HasRoles trait
use Spatie\Permission\Models\Role;     // Pastikan Role sudah diimpor

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles; // Tambahkan HasRoles trait

        // Tambahkan di dalam class User
    public function roles()
    {
    return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    }

    /**
     * Get role permissions and access levels
     *
     * @return array
     */
    public function getRolePermissions()
    {
        return [
            'admin' => [
                'description' => 'Full system access',
                'permissions' => [
                    'manage_users',
                    'manage_menu',
                    'manage_orders',
                    'manage_payments',
                    'manage_tables',
                    'manage_reservations',
                    'view_reports',
                    'system_settings'
                ]
            ],
            'manager' => [
                'description' => 'Table management access',
                'permissions' => [
                    'manage_tables',
                    'view_orders',
                    'view_reservations',
                    'view_reports'
                ]
            ],
            'kasir' => [
                'description' => 'Reservation management access',
                'permissions' => [
                    'manage_reservations',
                    'manage_orders',
                    'process_payments',
                    'view_menu'
                ]
            ],
            'customer' => [
                'description' => 'Basic customer access',
                'permissions' => [
                    'view_menu',
                    'place_orders',
                    'make_reservations',
                    'view_own_orders',
                    'view_own_reservations'
                ]
            ]
        ];
    }

    /**
     * Check if user has specific permission based on role
     *
     * @param string $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        $rolePermissions = $this->getRolePermissions();

        if (!isset($rolePermissions[$this->role])) {
            return false;
        }

        return in_array($permission, $rolePermissions[$this->role]['permissions']);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
    'name',
    'email',
    'password',
    'phone',
    'address',
    'role',
    'balance',
    'is_active',
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
    ];

    /**
     * Get the attendances for the user.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get the orders for the user.
     */
    public function orders()
    {
        return $this->hasMany(\App\Models\Order::class);
    }

    /**
     * Get the reservations for the user.
     */
    public function reservations()
    {
        return $this->hasMany(\App\Models\Reservation::class);
    }
}
