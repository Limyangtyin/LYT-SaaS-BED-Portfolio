<?php

namespace Database\Seeders;

use App\Models\Companies;
use Faker\Core\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompaniesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Companies::truncate();

        $json = File::get('database/data/companies.json');

        $companies = json_decode($json);

        foreach ($companies as $company) {
            Companies::create([
                'name' => $company->name,
                'city' => $company->city,
                'state' => $company->state,
                'country' => $company->country,
                'logo' => $company->logo,
            ]);
        }
    }
}
