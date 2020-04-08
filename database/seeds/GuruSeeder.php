<?php
use App\Teacher;
use App\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GuruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        for ($i = 0; $i < 50; $i++) {
            $guru = User::create([
                'name'              => $faker->username,
                'email'             => $faker->unique()->email,
                'email_verified_at' => now(),
                'password'          => Hash::make(12345678),
                'remember_token'    => Str::random(10),
                'menuroles'         => 'guru',
            ]);
            $guru->assignRole('guru');
            Teacher::create([
                "user_id"           => $guru->id,
                "nip"               => $faker->ean8(),
                "nama"              => $faker->titlemale . $faker->firstnamemale,
                "alamat"            => $faker->address,
                "jenjangpendidikan" => 'S1',
                "notelp"            => $faker->e164PhoneNumber(),
                "tanggalgabung"     => now(),
                "status"            => 1,
            ]);

        }

        for ($i = 0; $i < 50; $i++) {
            $user = User::create([
                'name'              => $faker->username,
                'email'             => $faker->unique()->email,
                'email_verified_at' => now(),
                'password'          => Hash::make(12345678),
                'remember_token'    => Str::random(10),
                'menuroles'         => 'guru',
            ]);
            $user->assignRole('guru');
            Teacher::create([
                "user_id"           => $user->id,
                "nip"               => $faker->ean8(),
                "nama"              => $faker->titlefemale . $faker->firstnamefemale,
                "alamat"            => $faker->address,
                "jenjangpendidikan" => 'S1',
                "notelp"            => $faker->e164PhoneNumber(),
                "tanggalgabung"     => now(),
                "status"            => 1,
            ]);

        }
    }
}
