<?php

namespace Database\Seeders;

use App\Models\PrmCompanies;
use App\Models\PrmConfig;
use App\Models\PrmMenus;
use App\Models\PrmRoleMenus;
use App\Models\PrmRoles;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User Add
        User::create([
            'username' => 'adm',
            'name' => 'Administrator',
            'email' => 'admin@zazara',
            'password' => Hash::make('admin123'),
        ]);
        // End User

        // Role Add
        PrmRoles::create([
            'id' => 1,
            'name' => 'Administrator',
        ]);
        // End Role

        // Menus Add
        PrmMenus::create([
            'id' => 1,
            'name' => 'Dashboard',
            'icon' => 'bxs-dashboard',
            'seq' => 1,
        ]);

        PrmMenus::create([
            'id' => 2,
            'name' => 'Purchase',
            'icon' => 'bxs-purchase-tag',
            'seq' => 2,
        ]);

        PrmMenus::create([
            'id' => 3,
            'name' => 'Sales',
            'icon' => 'bxs-offer',
            'action' => 'sales',
            'seq' => 3,
        ]);

        PrmMenus::create([
            'id' => 4,
            'name' => 'Expense',
            'icon' => 'bxs-credit-card',
            'seq' => 4,
        ]);

        PrmMenus::create([
            'id' => 5,
            'parent_id' => 4,
            'name' => 'New Expense',
            'action' => 'expanse/create',
            'seq' => 1,
            'is_create' => 1,
        ]);

        PrmMenus::create([
            'id' => 6,
            'parent_id' => 4,
            'name' => 'Expense Records',
            'action' => 'expanse',
            'seq' => 2,
            'is_create' => 1,
        ]);

        PrmMenus::create([
            'id' => 7,
            'parent_id' => 4,
            'name' => 'Expense Accounts',
            'seq' => 3,
            'is_create' => 1,
            'is_update' => 1,
            'is_status' => 0,
        ]);

        PrmMenus::create([
            'id' => 8,
            'name' => 'Bank & Cash',
            'icon' => 'bxs-bank',
            'seq' => 5,
            'is_show' => '0',
            'is_status' => '0',
        ]);

        PrmMenus::create([
            'id' => 9,
            'name' => 'Payments',
            'icon' => 'bxs-receipt',
            'seq' => 6,
        ]);

        PrmMenus::create([
            'id' => 10,
            'parent_id' => 9,
            'name' => 'Pay Payments',
            'action' => 'pay',
            'seq' => 1,
        ]);

        PrmMenus::create([
            'id' => 11,
            'parent_id' => 9,
            'name' => 'Receive Payments',
            'action' => 'receive',
            'seq' => 2,
        ]);

        PrmMenus::create([
            'id' => 12,
            'name' => 'Reports',
            'icon' => 'bxs-chalkboard',
            'seq' => 7,
        ]);

        PrmMenus::create([
            'id' => 13,
            'parent_id' => 12,
            'name' => 'Sales Report',
            'seq' => 1,
            'is_create' => 1,
        ]);

        PrmMenus::create([
            'id' => 14,
            'parent_id' => 12,
            'name' => 'Purchase Report',
            'seq' => 2,
            'is_create' => 1,
        ]);

        PrmMenus::create([
            'id' => 15,
            'parent_id' => 12,
            'name' => 'Expanse Report',
            'seq' => 3,
        ]);

        PrmMenus::create([
            'id' => 16,
            'parent_id' => 12,
            'name' => 'Profit Loss Report',
            'seq' => 4,
        ]);

        PrmMenus::create([
            'id' => 17,
            'parent_id' => 12,
            'name' => 'Day Book',
            'seq' => 5,
        ]);

        PrmMenus::create([
            'id' => 18,
            'name' => 'Masters',
            'icon' => 'bxs-cog',
            'seq' => 8,
        ]);

        PrmMenus::create([
            'id' => 19,
            'parent_id' => 18,
            'name' => 'Users',
            'action' => 'masters/users',
            'seq' => 1,
            'is_create' => 1,
            'is_update' => 1,
            'is_delete' => 1,
        ]);

        PrmMenus::create([
            'id' => 20,
            'parent_id' => 18,
            'name' => 'Roles',
            'action' => 'masters/roles',
            'seq' => 2,
            'is_create' => 1,
            'is_update' => 1,
            'is_delete' => 1,
        ]);

        PrmMenus::create([
            'id' => 21,
            'parent_id' => 18,
            'name' => 'Profile Company',
            'action' => 'masters/profile-company',
            'seq' => 3,
            'is_update' => 1,
        ]);

        PrmMenus::create([
            'id' => 22,
            'parent_id' => 18,
            'name' => 'Products',
            'action' => 'masters/products',
            'seq' => 4,
            'is_create' => 1,
            'is_update' => 1,
        ]);

        PrmMenus::create([
            'id' => 23,
            'parent_id' => 18,
            'name' => 'Suppliers',
            'action' => 'masters/suppliers',
            'seq' => 5,
            'is_create' => 1,
            'is_update' => 1,
            'is_delete' => 1,
        ]);

        PrmMenus::create([
            'id' => 24,
            'parent_id' => 18,
            'name' => 'Customers',
            'action' => 'masters/customers',
            'seq' => 6,
            'is_create' => 1,
            'is_update' => 1,
            'is_delete' => 1,
        ]);

        PrmMenus::create([
            'id' => 25,
            'parent_id' => 18,
            'name' => 'Configuration',
            'action' => 'masters/configuration',
            'seq' => 7,
            'is_update' => 1,
        ]);

        PrmMenus::create([
            'id' => 26,
            'parent_id' => 18,
            'name' => 'Category Account',
            'action' => 'masters/category-accounts',
            'seq' => 8,
            'is_create' => 1,
            'is_update' => 1,
            'is_delete' => 1,
        ]);

        PrmMenus::create([
            'id' => 27,
            'parent_id' => 18,
            'name' => 'Account',
            'action' => 'masters/accounts',
            'seq' => 9,
            'is_create' => 1,
            'is_update' => 1,
            'is_delete' => 1,
        ]);

        PrmMenus::create([
            'id' => 28,
            'parent_id' => 2,
            'name' => 'PO Tax',
            'action' => 'purchase',
            'seq' => 1,
            'is_create' => 1,
            'is_update' => 1,
        ]);

        PrmMenus::create([
            'id' => 29,
            'parent_id' => 2,
            'name' => 'PO Non Tax',
            'action' => 'purchase/non-tax',
            'seq' => 2,
            'is_create' => 1,
            'is_update' => 1,
        ]);

        PrmMenus::create([
            'id' => 30,
            'parent_id' => 3,
            'name' => 'SO Tax',
            'action' => 'sales',
            'seq' => 1,
            'is_create' => 1,
            'is_update' => 1,
        ]);

        PrmMenus::create([
            'id' => 31,
            'parent_id' => 3,
            'name' => 'SO Non Tax',
            'action' => 'sales/non-tax',
            'seq' => 2,
            'is_create' => 1,
            'is_update' => 1,
        ]);

        PrmMenus::create([
            'id' => 32,
            'parent_id' => 18,
            'name' => 'Payment Methods',
            'action' => 'masters/payment-methods',
            'seq' => 10,
            'is_create' => 1,
            'is_update' => 1,
            'is_delete' => 1,
        ]);
        //End Menus

        // Role Menus Added
        PrmRoleMenus::create([
            'role_id' => 1,
            'menu_id' => 1,
        ]);
        PrmRoleMenus::create([
            'role_id' => 1,
            'menu_id' => 2,
            'is_create' => 1,
            'is_update' => 1,
            'is_delete' => 1,
        ]);
        PrmRoleMenus::create([
            'role_id' => 1,
            'menu_id' => 3,
            'is_create' => 1,
            'is_update' => 1,
            'is_delete' => 1,
        ]);
        PrmRoleMenus::create([
            'role_id' => 1,
            'menu_id' => 4,
        ]);
        PrmRoleMenus::create([
            'role_id' => 1,
            'menu_id' => 5,
            'is_create' => 1,
        ]);
        PrmRoleMenus::create([
            'role_id' => 1,
            'menu_id' => 6,
            'is_create' => 1,
        ]);
        PrmRoleMenus::create([
            'role_id' => 1,
            'menu_id' => 7,
            'is_create' => 1,
            'is_update' => 1,
            'is_status' => 0,
        ]);
        PrmRoleMenus::create([
            'role_id' => 1,
            'menu_id' => 9,
        ]);
        PrmRoleMenus::create([
            'role_id' => 1,
            'menu_id' => 10,
        ]);
        PrmRoleMenus::create([
            'role_id' => 1,
            'menu_id' => 11,
        ]);
        PrmRoleMenus::create([
            'role_id' => 1,
            'menu_id' => 12,
        ]);
        PrmRoleMenus::create([
            'role_id' => 1,
            'menu_id' => 13,
            'is_create' => 1,
        ]);
        PrmRoleMenus::create([
            'role_id' => 1,
            'menu_id' => 14,
            'is_create' => 1,
        ]);
        PrmRoleMenus::create([
            'role_id' => 1,
            'menu_id' => 15,
        ]);
        PrmRoleMenus::create([
            'role_id' => 1,
            'menu_id' => 16,
        ]);
        PrmRoleMenus::create([
            'role_id' => 1,
            'menu_id' => 17,
        ]);
        PrmRoleMenus::create([
            'role_id' => 1,
            'menu_id' => 18,
        ]);
        PrmRoleMenus::create([
            'role_id' => 1,
            'menu_id' => 19,
            'is_create' => 1,
            'is_update' => 1,
            'is_delete' => 1,
        ]);
        PrmRoleMenus::create([
            'role_id' => 1,
            'menu_id' => 20,
            'is_create' => 1,
            'is_update' => 1,
            'is_delete' => 1,
        ]);
        PrmRoleMenus::create([
            'role_id' => 1,
            'menu_id' => 21,
            'is_update' => 1,
        ]);
        PrmRoleMenus::create([
            'role_id' => 1,
            'menu_id' => 22,
            'is_create' => 1,
            'is_update' => 1,
        ]);
        PrmRoleMenus::create([
            'role_id' => 1,
            'menu_id' => 23,
            'is_create' => 1,
            'is_update' => 1,
            'is_delete' => 1,
        ]);
        PrmRoleMenus::create([
            'role_id' => 1,
            'menu_id' => 24,
            'is_create' => 1,
            'is_update' => 1,
            'is_delete' => 1,
        ]);
        PrmRoleMenus::create([
            'role_id' => 1,
            'menu_id' => 25,
            'is_update' => 1,
        ]);
        PrmRoleMenus::create([
            'role_id' => 1,
            'menu_id' => 26,
            'is_create' => 1,
            'is_update' => 1,
            'is_delete' => 1,
        ]);
        PrmRoleMenus::create([
            'role_id' => 1,
            'menu_id' => 27,
            'is_create' => 1,
            'is_update' => 1,
            'is_delete' => 1,
        ]);
        PrmRoleMenus::create([
            'role_id' => 1,
            'menu_id' => 28,
            'is_create' => 1,
            'is_update' => 1,
        ]);
        PrmRoleMenus::create([
            'role_id' => 1,
            'menu_id' => 29,
            'is_create' => 1,
            'is_update' => 1,
        ]);
        PrmRoleMenus::create([
            'role_id' => 1,
            'menu_id' => 30,
            'is_create' => 1,
            'is_update' => 1,
        ]);
        PrmRoleMenus::create([
            'role_id' => 1,
            'menu_id' => 31,
            'is_create' => 1,
            'is_update' => 1,
        ]);
        PrmRoleMenus::create([
            'role_id' => 1,
            'menu_id' => 32,
            'is_create' => 1,
            'is_update' => 1,
            'is_delete' => 1,
        ]);
        // End Role Menus

        // Company Added
        PrmCompanies::create([
            'name' => 'PT Ellia Sukses Bersama',
        ]);
        // End Company

        // Config Added
        PrmConfig::create([
            'id' => 1,
            'code' => 'ppn',
            'name' => 'PPN',
            'type' => 'decimal',
            'value' => '11',
        ]);

        PrmConfig::create([
            'id' => 2,
            'code' => 'pos',
            'name' => 'PO Sign',
            'type' => 'varchar',
            'value' => 'PT. ELLIA SUKSES BERSAMA',
        ]);

        PrmConfig::create([
            'id' => 3,
            'code' => 'sos',
            'name' => 'SO Sign',
            'type' => 'varchar',
            'value' => 'PT. ELLIA SUKSES BERSAMA',
        ]);
        // End Config

        // Category Account Added
        PrmConfig::create([
            'id' => 1,
            'name' => 'Assets',
        ]);
        PrmConfig::create([
            'id' => 2,
            'name' => 'Liabilities',
        ]);
        PrmConfig::create([
            'id' => 3,
            'name' => 'Equity',
        ]);
        PrmConfig::create([
            'id' => 4,
            'name' => 'Revenue',
        ]);
        PrmConfig::create([
            'id' => 5,
            'name' => 'Expanses',
        ]);
        // End Category Account
    }
}
