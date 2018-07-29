<?php

use Illuminate\Database\Seeder;

class CustomersSeeder extends Seeder
{
    /**
     * Run the users table seed.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Customer::class, 1000)->create()->each(function ($customer) {
            $customer->update([
                'company_id' => factory(App\Company::class)->create()->id,
            ]);

            $customer->actions()->saveMany(factory(App\Action::class, 500)->make());
        });
    }
}
