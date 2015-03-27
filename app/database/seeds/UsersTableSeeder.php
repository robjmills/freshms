<?php

class UsersTableSeeder extends Seeder {

	public function run()
	{
	       // seed the user
               $user = User::create([
                        'name'      =>  'Rob Mills',
                        'email'     =>  'rob.mills@liquidshop.com',
                        'mobile'    =>  '+44 7793 078335',
                        'organisation_id'   =>  '1',
                        'paused'    =>  '0',
                        'admin'     =>  '1'
	       ]);

               // create the many-to-many pivot to alert
               $user->alerts()->sync([1]);
	}

}