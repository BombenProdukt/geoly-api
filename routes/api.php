<?php

declare(strict_types=1);

use App\Http\Controllers\InfoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Http\Controllers\JsonApiController;
use LaravelJsonApi\Laravel\Routing\Relationships;
use LaravelJsonApi\Laravel\Routing\ResourceRegistrar;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/v1/info/{ip}', InfoController::class);

JsonApiRoute::server('v1')->prefix('v1')->resources(function (ResourceRegistrar $server): void {
    $server
        ->resource('autonomous-systems', JsonApiController::class)
        ->relationships(function (Relationships $relationships): void {
            $relationships->hasMany('contacts')->readOnly();
            $relationships->hasMany('neighbours')->readOnly();
            $relationships->hasMany('prefixes')->readOnly();
        })
        ->readOnly();

    $server
        ->resource('cities', JsonApiController::class)
        ->relationships(function (Relationships $relationships): void {
            $relationships->hasOne('country')->readOnly();
        })
        ->readOnly();

    $server
        ->resource('continents', JsonApiController::class)
        ->relationships(function (Relationships $relationships): void {
            $relationships->hasMany('countries')->readOnly();
        })
        ->readOnly();

    $server
        ->resource('countries', JsonApiController::class)
        ->relationships(function (Relationships $relationships): void {
            $relationships->hasOne('continent')->readOnly();
            $relationships->hasMany('internetProtocolBlocks')->readOnly();
        })
        ->readOnly();

    $server
        ->resource('internet-protocol-addresses', JsonApiController::class)
        ->relationships(function (Relationships $relationships): void {
            $relationships->hasOne('autonomousSystem')->readOnly();
            $relationships->hasOne('continent')->readOnly();
            $relationships->hasOne('country')->readOnly();
            $relationships->hasOne('subdivisionPrimary')->readOnly();
            $relationships->hasOne('subdivisionSecondary')->readOnly();
            $relationships->hasOne('city')->readOnly();
            $relationships->hasOne('location')->readOnly();
            $relationships->hasOne('riskyAddress')->readOnly();
        })
        ->readOnly();

    $server
        ->resource('internet-protocol-blocks', JsonApiController::class)
        ->relationships(function (Relationships $relationships): void {
            $relationships->hasOne('country')->readOnly();
        })
        ->readOnly();

    $server
        ->resource('locations', JsonApiController::class)
        ->relationships(function (Relationships $relationships): void {
            $relationships->hasOne('city')->readOnly();
        })
        ->readOnly();

    $server
        ->resource('risky-addresses', JsonApiController::class)
        ->readOnly();

    $server
        ->resource('subdivisions', JsonApiController::class)
        ->relationships(function (Relationships $relationships): void {
            $relationships->hasOne('city')->readOnly();
        })
        ->readOnly();
});
