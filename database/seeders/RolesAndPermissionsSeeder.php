<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions

        Permission::create(['name' => 'view admin panel']);

        Permission::create(['name' => 'view geos']);
        Permission::create(['name' => 'create geos']);
        Permission::create(['name' => 'edit geos']);
        Permission::create(['name' => 'delete geos']);
        Permission::create(['name' => 'force delete geos']);

        Permission::create(['name' => 'view news']);
        Permission::create(['name' => 'create news']);
        Permission::create(['name' => 'edit news']);
        Permission::create(['name' => 'delete news']);
        Permission::create(['name' => 'force delete news']);

        Permission::create(['name' => 'view pages']);
        Permission::create(['name' => 'create pages']);
        Permission::create(['name' => 'edit pages']);
        Permission::create(['name' => 'delete pages']);
        Permission::create(['name' => 'force delete pages']);


        Permission::create(['name' => 'view vehicle_types']);
        Permission::create(['name' => 'create vehicle_types']);
        Permission::create(['name' => 'edit vehicle_types']);
        Permission::create(['name' => 'delete vehicle_types']);
        Permission::create(['name' => 'force delete vehicle_types']);


        Permission::create(['name' => 'view orders']);
        Permission::create(['name' => 'create orders']);
        Permission::create(['name' => 'edit orders']);
        Permission::create(['name' => 'delete orders']);
        Permission::create(['name' => 'force delete orders']);


        Permission::create(['name' => 'view claims']);
        Permission::create(['name' => 'create claims']);
        Permission::create(['name' => 'edit claims']);
        Permission::create(['name' => 'delete claims']);
        Permission::create(['name' => 'force delete claims']);

        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'edit users']);
        Permission::create(['name' => 'delete users']);
        Permission::create(['name' => 'force delete users']);

        // Create roles and assign permissions

        /** @var Role $admin */
        $admin = Role::create(['name' => 'admin']);

        /** @var Role $dealer_manager */
        $dealer_manager = Role::create(['name' => 'dealer_manager']);

        /** @var Role $leasing_manager */
        $leasing_manager = Role::create(['name' => 'leasing_manager']);

        /** @var Role $client */
        $client = Role::create(['name' => 'client']);

        $geosCRUD = [
            'view geos',
            'create geos',
            'edit geos',
            'delete geos',
            'force delete geos',
        ];

        $newsCRUD = [
            'view news',
            'create news',
            'edit news',
            'delete news',
            'force delete news',
        ];

        $pagesCRUD = [
            'view pages',
            'create pages',
            'edit pages',
            'delete pages',
            'force delete pages',
        ];

        $typesCRUD = [
            'view vehicle_types',
            'create vehicle_types',
            'edit vehicle_types',
            'delete vehicle_types',
            'force delete vehicle_types',
        ];

        $ordersCRUD = [
            'view orders',
            'create orders',
            'edit orders',
            'delete orders',
            'force delete orders',
        ];

        $claimsForAdmin = [
            'view claims',
            'delete claims',
            'force delete claims',
        ];

        $usersCRUD = [
            'view users',
            'create users',
            'edit users',
            'delete users',
            'force delete users',
        ];

        $admin->givePermissionTo([
            'view admin panel',

            //Users
            $usersCRUD,

            // Geos
            $geosCRUD,

            // Pages
            $pagesCRUD,

            // News
            $newsCRUD,

            // Orders
            $ordersCRUD,

            // Types
            $typesCRUD,

            $claimsForAdmin
        ]);


        /** @var User $user */
        $user = User::where('id', 1)->first();
        $user->assignRole($admin);

        /** @var User $user */
        $user = User::where('id', 2)->first();
        $user->assignRole($dealer_manager);

        /** @var User $user */
        $user = User::where('id', 3)->first();
        $user->assignRole($leasing_manager);

        /** @var User $user */
        $user = User::where('id', 4)->first();
        $user->assignRole($client);
    }
}
