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
        /**
         * Decode auth header - may need htaccess to make this work on Fortrabbit and other fpm based hosts
         * http://fortrabbit.com/docs/how-to/php/use-http-auth
         * could actually use this $_SERVER['PHP_AUTH_USER']
         */
        $auth_parts = explode(':',base64_decode(substr($payload, 6)));
        $organisation =  Organisation::where('api_token',$auth_parts[0])->first();

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

                    $sid = "AC1b13d8272d023e7be2985b4291069767"; // Your Account SID from www.twilio.com/user/account
                    $token = "dccc17cff28c88f1bde2107a9d0d0742"; // Your Auth Token from www.twilio.com/user/account

                    foreach ( $alert->users as $user ) {

                        $client = new Services_Twilio($sid, $token);
                        $message = $client->account->messages->sendMessage(
                            '+441902504251', // From a valid Twilio number
                            $user->mobile, // Text this number
                            "Test SMS"
                        );

                    }

                    if(isset($message)){
                        $response = Response::json([
                                'error' => false,
                                'message' => $message,
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