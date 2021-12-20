<?php

namespace Database\Seeders;

use App\Models\PromoCode;
use App\Models\User;
use Illuminate\Database\Seeder;

class PromoCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PromoCode::factory()->count(50)->create();
        $codes = PromoCode::all();
        $users = User::all();
        foreach($users as $user){
            foreach($codes as $code)
            {
                $user->wallet->balance += $code->amount;
                $user->promoCodes()->attach([$code->id]);
            }
        }
    }
}
