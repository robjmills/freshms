<?php

class OrganisationsTableSeeder extends Seeder {

	public function run()
	{
        Organisation::create([
            'name'  =>  'Devteam Inc',
            'api_token' =>  hash('sha256','myapikeytest',false)
        ]);
	}

}