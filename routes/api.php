<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app('Dingo\Api\Routing\Router');

$api -> version('v1', [
    'namespace' => 'App\Http\Controllers\Api',
    'middleware' => 'throttle:30',
], function($api){
    //for store branch
    $api -> post('addStoreBranch', 'StoreBranchController@create')
        ->name('api.addStoreBranch.create');

    $api -> put('updateStoreBranch', 'StoreBranchController@update')
        ->name('api.updateStoreBranch.update');

    $api -> put('moveStoreBranch', 'StoreBranchController@move')
        -> name('api.moveStoreBranch.move');

    $api -> get('viewStoreBranch', 'StoreBranchController@view')
        -> name('api.viewStoreBranch.view');

    $api -> get('viewGroupStoreBranch', 'StoreBranchController@viewWithItsChildren')
        -> name('api.viewGroupStoreBranch.view');

    $api -> get('viewAllStoreBranch', 'StoreBranchController@viewAll')
        -> name('api.viewAllStoreBranch.view');

    $api -> delete('deleteStoreBranch', 'StoreBranchController@delete')
        -> name('api.deleteGroupStoreBranch.delete');


});