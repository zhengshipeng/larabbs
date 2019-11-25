<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = app(Faker\Generator::class);
        $avatars = [
            'http://larabbs.net/uploads/images/avatars/201911/20/1_1574218096_KqLN123cWy.jpg',
            'http://larabbs.net/uploads/images/avatars/201911/20/3_1574228920_1MLo7wusoh.png',
        ];
        $users = factory(\App\Models\User::class)->times(10)->make()->each(function ($user, $index)  use ($faker, $avatars) {
            $user->avatar = $faker->randomElement($avatars);
        });

        $user_array = $users->makeVisible(['password', 'remember_token'])->toArray();

        \App\Models\User::insert($user_array);

        $user = \App\Models\User::find(2);
        $user->assignRole('Maintainer');

        $user = \App\Models\User::find(1);
        $user->assignRole('Founder');

    }
}
