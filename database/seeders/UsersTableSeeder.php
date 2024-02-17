<?php

namespace Database\Seeders;


use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use jeremykenedy\LaravelRoles\Models\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $profile = new Profile();
        $rootRole = Role::whereName('Root')->first();
        $adminRole = Role::whereName('Admin')->first();
        $managerRole = Role::whereName('Manager')->first();
        $supervisorRole = Role::whereName('Loan Officer')->first();
        $partnerRole = Role::whereName('Partner')->first();
        $userRole = Role::whereName('Agent')->first();
        $funderRole = Role::whereName('Funder')->first();
        $clientRole = Role::whereName('Client')->first();

        $seededAdminEmail = 'kaynerd26@gmail.com';
        $user = User::where('email', '=', $seededAdminEmail)->first();
        if ($user === null) {
            $user = User::create([
                'name'                           => 'kaynerd',
                'first_name'                     => 'Kauma',
                'last_name'                      => 'Mbewe',
                'email'                          => $seededAdminEmail,
                'natid'                          => '497667/67/1',
                'mobile'                         => '979113333',
                'bot_number'                         => '263773418009',
                'password'                       => Hash::make('IAmR00t!@#'),
                'token'                          => str_random(64),
                'utype'                          => 'System',
                'locale'                          => 1,
                'activated'                      => true,
                'signup_confirmation_ip_address' => $faker->ipv4,
                'admin_ip_address'               => $faker->ipv4,
            ]);

            $user->profile()->save($profile);
            $user->attachRole($rootRole);
            $user->save();
        }

        $seededAdminEmail = 'honest@vokers.co.zw';
        $user = User::where('email', '=', $seededAdminEmail)->first();
        if ($user === null) {
            $user = User::create([
                'name'                           => 'hrudo',
                'first_name'                     => 'Honest',
                'last_name'                      => 'Rudo',
                'email'                          => $seededAdminEmail,
                'natid'                          => '23-8321711-F-22',
                'mobile'                         => '773222484',
                'bot_number'                         => '263773222484',
                'password'                       => Hash::make('IAmAdm1n!@#'),
                'token'                          => str_random(64),
                'utype'                          => 'System',
                'locale'                          => 1,
                'activated'                      => true,
                'signup_confirmation_ip_address' => $faker->ipv4,
                'admin_ip_address'               => $faker->ipv4,
            ]);

            $user->profile()->save($profile);
            $user->attachRole($adminRole);
            $user->save();
        }

        $user = User::where('email', '=', 'manager@eshagi.com')->first();
        if ($user === null) {
            $user = User::create([
                'name'                           => $faker->userName,
                'first_name'                     => $faker->firstName,
                'last_name'                      => $faker->lastName,
                'email'                          => 'manager@eshagi.com',
                'natid'                          => $faker->e164PhoneNumber,
                'mobile'                          => $faker->phoneNumber,
                'bot_number'                          => $faker->phoneNumber,
                'password'                       => Hash::make('password'),
                'token'                          => str_random(64),
                'utype'                          => 'System',
                'locale'                          => 1,
                'activated'                      => true,
                'signup_ip_address'              => $faker->ipv4,
                'signup_confirmation_ip_address' => $faker->ipv4,
            ]);

            $user->profile()->save(new Profile());
            $user->attachRole($managerRole);
            $user->save();
        }

        $user = User::where('email', '=', 'supervisor@eshagi.com')->first();
        if ($user === null) {
            $user = User::create([
                'name'                           => $faker->userName,
                'first_name'                     => $faker->firstName,
                'last_name'                      => $faker->lastName,
                'email'                          => 'supervisor@eshagi.com',
                'natid'                          => $faker->e164PhoneNumber,
                'mobile'                          => $faker->phoneNumber,
                'bot_number'                          => $faker->phoneNumber,
                'password'                       => Hash::make('password'),
                'token'                          => str_random(64),
                'utype'                          => 'System',
                'locale'                          => 1,
                'activated'                      => true,
                'signup_ip_address'              => $faker->ipv4,
                'signup_confirmation_ip_address' => $faker->ipv4,
            ]);

            $user->profile()->save(new Profile());
            $user->attachRole($supervisorRole);
            $user->save();
        }

        $user = User::where('email', '=', 'partner@eshagi.com')->first();
        if ($user === null) {
            $user = User::create([
                'name'                           => $faker->userName,
                'first_name'                     => $faker->firstName,
                'last_name'                      => $faker->lastName,
                'email'                          => 'partner@eshagi.com',
                'natid'                          => '497667/67/1',
                'mobile'                         => '979113333',
                'bot_number'                         => '263773418009',
                'password'                       => Hash::make('password'),
                'token'                          => str_random(64),
                'utype'                          => 'Partner',
                'locale'                          => 1,
                'activated'                      => true,
                'signup_ip_address'              => $faker->ipv4,
                'signup_confirmation_ip_address' => $faker->ipv4,
            ]);

            $user->profile()->save(new Profile());
            $user->attachRole($partnerRole);
            $user->save();
        }

        $user = User::where('email', '=', 'user@eshagi.com')->first();
        if ($user === null) {
            $user = User::create([
                'name'                           => $faker->userName,
                'first_name'                     => $faker->firstName,
                'last_name'                      => $faker->lastName,
                'email'                          => 'user@eshagi.com',
                'natid'                          => $faker->e164PhoneNumber,
                'mobile'                          => $faker->phoneNumber,
                'bot_number'                          => $faker->phoneNumber,
                'password'                       => Hash::make('password'),
                'token'                          => str_random(64),
                'utype'                          => 'System',
                'locale'                          => 1,
                'activated'                      => true,
                'signup_ip_address'              => $faker->ipv4,
                'signup_confirmation_ip_address' => $faker->ipv4,
            ]);

            $user->profile()->save(new Profile());
            $user->attachRole($userRole);
            $user->save();
        }

        $user = User::where('email', '=', 'company@funder.com')->first();
        if ($user === null) {
            $user = User::create([
                'name'                           => $faker->userName,
                'first_name'                     => $faker->firstName,
                'last_name'                      => $faker->lastName,
                'email'                          => 'company@funder.com',
                'natid'                          => $faker->e164PhoneNumber,
                'mobile'                          => $faker->phoneNumber,
                'bot_number'                          => $faker->phoneNumber,
                'password'                       => Hash::make('password'),
                'token'                          => str_random(64),
                'utype'                          => 'System',
                'locale'                          => 1,
                'activated'                      => true,
                'signup_ip_address'              => $faker->ipv4,
                'signup_confirmation_ip_address' => $faker->ipv4,
            ]);

            $user->profile()->save(new Profile());
            $user->attachRole($funderRole);
            $user->save();
        }

        $user = User::where('email', '=', 'client@gmail.com')->first();
        if ($user === null) {
            $user = User::create([
                'name'                           => $faker->userName,
                'first_name'                     => $faker->firstName,
                'last_name'                      => $faker->lastName,
                'email'                          => 'client@gmail.com',
                'natid'                          => $faker->e164PhoneNumber,
                'mobile'                          => $faker->phoneNumber,
                'bot_number'                          => $faker->phoneNumber,
                'password'                       => Hash::make('password'),
                'token'                          => str_random(64),
                'utype'                          => 'Client',
                'locale'                          => 1,
                'activated'                      => true,
                'signup_ip_address'              => $faker->ipv4,
                'signup_confirmation_ip_address' => $faker->ipv4,
            ]);

            $user->profile()->save(new Profile());
            $user->attachRole($clientRole);
            $user->save();
        }

        // Seed test users
        // $user = factory(App\Models\Profile::class, 5)->create();
        // $users = User::All();
        // foreach ($users as $user) {
        //     if (!($user->isAdmin()) && !($user->isUnverified())) {
        //         $user->attachRole($userRole);
        //     }
        // }
    }
}
