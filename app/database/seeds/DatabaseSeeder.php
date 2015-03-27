<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		$this->call('OrganisationsTableSeeder');
		$this->call('AlertsTableSeeder');
		$this->call('UsersTableSeeder');
        // $this->call('AssetsTableSeeder'); // no point seeding assets when actual file won't exist
	}

}