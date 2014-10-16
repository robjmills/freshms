<?php

class AlertsTableSeeder extends Seeder {

	public function run()
	{
        Alert::create([
            'label' =>  'OOH',
            'organisation_id'   =>  '1'
        ]);
	}

}