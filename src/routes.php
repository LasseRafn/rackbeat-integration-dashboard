<?php
/**
 * Created by PhpStorm.
 * User: kgbot
 * Date: 2/21/19
 * Time: 6:13 PM
 */

use KgBot\RackbeatDashboard\Http\Middleware\CheckDashboardToken;

Route::group( [ 'prefix' => 'rackbeat-integration-dashboard', 'namespace' => 'KgBot\RackbeatDashboard\Http\Controllers', 'middleware' => [ 'web', CheckDashboardToken::class ] ], function () {

	Route::get( 'jobs/{rackbeat_account_id}', 'JobsController@index' )->name( 'dashboard-jobs.index' );

	Route::post( 'retry/{job}', 'JobsController@retry' )->name( 'dashboard-jobs.retry' );

	Route::delete( 'jobs/{job}', 'JobsController@delete' )->name( 'dashboard-jobs.delete' );

	Route::get( 'job/{job}', 'JobsController@details' )->name( 'dashboard-jobs.details' );
} );