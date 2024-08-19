<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'avatar'=>'bunny.jpg',
            'name'=>'jean',
            'email' =>'houafojean@gmail.com',
            'password'=>Hash::make('123456789'),
            'type'=>1,
            'status'=> "unlock"
        ]);
        User::create([
            'avatar'=>'bunny.jpg',
            'name'=>'alexis',
            'email' =>'fokamalexis@gmail.com',
            'password'=>Hash::make('123456789'),
            'type'=>1,
            'status'=> "unlock"
        ]);
        User::create([
            'avatar'=>'bunny.jpg',
            'name'=>'franck',
            'email' =>'mflgroup225@gmail.com',
            'password'=>Hash::make('123456789'),
            'type'=>1,
            'status'=> "unlock"
        ]);
    }
}
