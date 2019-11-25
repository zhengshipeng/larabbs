<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SeedRolesAndPermissionsData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 清除缓存
        app(Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        // 创建权限
        \Spatie\Permission\Models\Permission::create(['name' => 'manage_contents']);
        \Spatie\Permission\Models\Permission::create(['name' => 'manage_users']);
        \Spatie\Permission\Models\Permission::create(['name' => 'edit_settings']);

        // 创建站长角色，并赋予权限
        $founder = \Spatie\Permission\Models\Role::create(['name' => 'Founder']);
        $founder->givePermissionTo('manage_contents');
        $founder->givePermissionTo('manage_users');
        $founder->givePermissionTo('edit_settings');

        // 创建管理员角色，并赋予权限
        $maintainer = \Spatie\Permission\Models\Role::create(['name' => 'Maintainer']);
        $maintainer->givePermissionTo('manage_contents');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 清除缓存
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        // 清空数据表数据
        $tableNames = config('permission.table_names');

        \App\Models\Model::unguard();
        \Illuminate\Support\Facades\DB::table($tableNames['role_has_permissions'])->delete();
        \Illuminate\Support\Facades\DB::table($tableNames['model_has_roles'])->delete();
        \Illuminate\Support\Facades\DB::table($tableNames['model_has_permissions'])->delete();
        \Illuminate\Support\Facades\DB::table($tableNames['roles'])->delete();
        \Illuminate\Support\Facades\DB::table($tableNames['permissions'])->delete();
        \App\Models\Model::reguard();
    }
}
