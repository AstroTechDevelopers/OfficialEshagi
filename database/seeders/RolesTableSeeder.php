<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
         * Role Types
         *
         */
        $RoleItems = [
            [
                'name'        => 'Root',
                'slug'        => 'root',
                'description' => 'Root Role',
                'level'       => 10,
            ],
            [
                'name'        => 'Admin',
                'slug'        => 'admin',
                'description' => 'Admin Role',
                'level'       => 9,
            ],
            [
                'name'        => 'Manager',
                'slug'        => 'manager',
                'description' => 'Manager Role',
                'level'       => 8,
            ],
            [
                'name'        => 'Group',
                'slug'        => 'group',
                'description' => 'Group role is a role for the Astro Africa Tech group employees, who are not limited by locale to view information or actions.',
                'level'       => 8 ,
            ],
            [
                'name'        => 'Loan Officer',
                'slug'        => 'loansofficer',
                'description' => 'Supervisor or the Loan officer role responsible for checking KYC verification',
                'level'       => 7 ,
            ],
            [
                'name'        => 'Sales Administrator',
                'slug'        => 'salesadmin',
                'description' => 'Sales admin role who can pull performance reports as well as apply for loans',
                'level'       => 7 ,
            ],
            [
                'name'        => 'Partner',
                'slug'        => 'partner',
                'description' => 'Partner role houses the merchant or agent roles in the system',
                'level'       => 6,
            ],
            [
                'name'        => 'Agent',
                'slug'        => 'agent',
                'description' => 'Agent role is the eshagi agent role or call center agent',
                'level'       => 5,
            ],
            [
                'name'        => 'Field Agent',
                'slug'        => 'fielder',
                'description' => 'Field Agent role is the agent role for field agent.',
                'level'       => 4,
            ],
            [
                'name'        => 'Astrogent',
                'slug'        => 'astrogent',
                'description' => 'Astrogent role is the agent role for home agents.',
                'level'       => 4,
            ],
            [
                'name'        => 'Funder',
                'slug'        => 'funder',
                'description' => 'Funder Role',
                'level'       => 3,
            ],
            [
                'name'        => 'Representative',
                'slug'        => 'representative',
                'description' => 'Representative Role',
                'level'       => 2,
            ],
            [
                'name'        => 'RedSphere',
                'slug'        => 'redsphere',
                'description' => 'RedSphere role to approve KYC information within the system and/or via middleware.',
                'level'       => 2,
            ],
            [
                'name'        => 'Womens Bank',
                'slug'        => 'womensbank',
                'description' => 'Womens Bank role to approve KYC information within the system and/or via middleware.',
                'level'       => 2,
            ],
            [
                'name'        => 'Client',
                'slug'        => 'client',
                'description' => 'Client Role',
                'level'       => 1,
            ]
        ];

        /*
         * Add Role Items
         *
         */
        foreach ($RoleItems as $RoleItem) {
            $newRoleItem = config('roles.models.role')::where('slug', '=', $RoleItem['slug'])->first();
            if ($newRoleItem === null) {
                $newRoleItem = config('roles.models.role')::create([
                    'name'          => $RoleItem['name'],
                    'slug'          => $RoleItem['slug'],
                    'description'   => $RoleItem['description'],
                    'level'         => $RoleItem['level'],
                ]);
            }
        }
    }
}
