<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    protected $toTruncate = ['users'];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $user = User::create([
            'first_name' => 'Kıvanç',
            'last_name'  => 'İnci',
            'email'      => 'kivanc@test.com',
            'username'   => 'kvnc-test',
            'password'   => bcrypt('123456'),
        ]);
        $wallet = Wallet::create(
            [
                'user_id' => $user->id,
                'balance'  => 0.00,
            ]
        );
    }
}
