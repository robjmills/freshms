<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('hello');
});

Route::resource('users', 'UsersController');
Route::resource('organisations', 'OrganisationsController');
Route::resource('alerts', 'AlertsController');

Route::group(['prefix' => 'api/v1'/*, 'before' => 'auth.token'*/], function() {
    Route::post('/notify/{alertid}', function ($alertid) {

        $payload = Request::header('authorization');
        $auth = $_SERVER['PHP_AUTH_USER'];
        $organisation =  Organisation::where('api_token',$auth)->first();

        /**
         * No auth header or auth header incorrect
         */
        if(!$payload || !$organisation) {
            $response = Response::json([
                    'error' => true,
                    'message' => 'Not authenticated',
                    'code' => 401],
                401
            );
        } else {
            $alert = Alert::where('id',$alertid)->first();

            /**
             * No such alert
             */
            if ( !$alert ){
                $response = Response::json([
                        'error' => true,
                        'message' => 'Not Found',
                        'code' => 404],
                    404
                );
            } else {
                /**
                 * Alert exists but doesn't belong to this organisation
                 */
                if ( $alert->organisation_id !=  $organisation->id ) {
                    $response = Response::json([
                            'error' => true,
                            'message' => 'Forbidden',
                            'code' => 403],
                        403
                    );
                }else{
                    /**
                     * it's all good
                     */

                    $sid = getenv('TWILIO_SID'); // Your Account SID from www.twilio.com/user/account
                    $token = getenv('TWILIO_TOKEN'); // Your Auth Token from www.twilio.com/user/account

                    foreach ( $alert->users as $user ) {

                        $client = new Services_Twilio($sid, $token);
                        $message = $client->account->messages->sendMessage(
                            getenv('TWILIO_TEL_NUMBER'), // From a valid Twilio number
                            $user->mobile, // Text this number
                            "Test SMS"
                        );

                    }

                    if(isset($message)){
                        $response = Response::json([
                                'error' => false,
                                'message' => "SMS Sent",
                                'code' => 200],
                            200
                        );
                    } else {
                        dd($client);
                    }
                }
            }
        }

        return $response;

        /**
         * next steps:
         * check alert is authed to organisation
         * return response to freshdesk
         * get mobile numbers of users attached to alert
         * call twilio service with numbers and content
         * add twilio response to log
         * adjust user credits (or charge user)
         */

    });
});