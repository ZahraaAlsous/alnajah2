<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;

class First_Admin extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create($this->admindata());
    }

    private function admindata()
    {
        return[
            'user_id'=>'1'
        ];
    }
}
