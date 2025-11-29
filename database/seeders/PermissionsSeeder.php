<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::updateOrCreate(['name' => 'admin'], [
            'guard_name' => 'web',
            'display_name' => 'Administrator',
        ]);

        // $this->manualCreatePermissions($role);
        $this->autoCreatePermissions($role);
    }

    /**
     * Manual Permissions Example
     */
    private function manualCreatePermissions($role): void
    {
        $permissions = [
            [
                'name' => 'users-create',
                'guard_name' => 'web',
                'group_name' => 'User',
                'display_name' => 'Create User',
            ],
            [
                'name' => 'users-edit',
                'guard_name' => 'web',
                'group_name' => 'User',
                'display_name' => 'Edit User',
            ],
            [
                'name' => 'users-delete',
                'guard_name' => 'web',
                'group_name' => 'User',
                'display_name' => 'Delete User',
            ],
            [
                'name' => 'users-view',
                'guard_name' => 'web',
                'group_name' => 'User',
                'display_name' => 'View User',
            ],
            [
                'name' => 'users-list',
                'guard_name' => 'web',
                'group_name' => 'User',
                'display_name' => 'List Users',
            ],
        ];

        foreach ($permissions as $permission) {
            $perm = Permission::updateOrCreate(
                ['name' => $permission['name'], 'guard_name' => $permission['guard_name']],
                [
                    'group_name' => $permission['group_name'],
                    'display_name' => $permission['display_name']
                ]
            );
            $role->givePermissionTo($perm);
        }
    }

    /**
     * Auto Create Permissions based on Models Folder inside app/Models
     */
    private function autoCreatePermissions($role)
    {
        $modelPath = app_path('Models');
        $modelFiles = File::files($modelPath);

        foreach ($modelFiles as $file) {
            $model = pathinfo($file->getFilename(), PATHINFO_FILENAME);
            $crudActions = ['create', 'edit', 'delete', 'view', 'list'];

            foreach ($crudActions as $action) {

                $permission = Permission::updateOrCreate(
                    ['name' => $action . '-' . strtolower($model), 'guard_name' => 'web'],
                    [
                        'group_name' => $model,
                        'display_name' => ucfirst($action),
                    ]
                );
                $role->givePermissionTo($permission);
            }
        }
    }
}
