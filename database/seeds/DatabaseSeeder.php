<?php

use App\Role;
use App\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesTableSeeder::class);

        factory(User::class, 19)->create();
        factory(User::class, 1)->create(['role_id' => Role::where('name', 'admin')->first()->id]);
    }
}
