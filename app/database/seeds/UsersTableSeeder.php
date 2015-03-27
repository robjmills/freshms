<?php

class UsersTableSeeder extends Seeder {

	public function run()
	{
	       User::create([
                'name'      =>  'Rob Mills',
                'email'     =>  'rob.mills@liquidshop.com',
                'mobile'    =>  '+4477930783335',
                'organisation_id'   =>  '1',
                'paused'    =>  '0',
                'admin'     =>  '1'
		]);
	}

}