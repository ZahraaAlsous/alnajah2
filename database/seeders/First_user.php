<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class First_user extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create($this->userdata());
    }

    private function userdata(){
        return [
            'first_name'=>'jamal',
            'last_name'=>'mrshed',
            'mother_name'=>'jhms',
            'father_name'=>'dnbciuvdkjv',
            'birthday'=>'2024/5/1',
            'gender'=>1,
            'phone'=>'095954135',
            'address'=>'dhnciaetyfyga',
            'year'=>'2024',
            'email'=>'jam@gmail.com',
            'password'=>123123123,
            'conf_password'=>123123123,
            'user_type'=>'admin',
        ];
    }
}
