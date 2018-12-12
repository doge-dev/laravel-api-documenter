<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Middleware Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default middleware used by the
    | LaravelAPIDocumenter class. Feel free to tweak each of these messages.
    |
    */

    "auth" => [
        "name"        => "Authentication",
        "description" => "Requires for the user to be authenticated",
    ],

    "auth_client" => [
        "name"        => "Client API",
        "description" => "The application accessing this request must be authenticated using a Client Grant",
    ],

    "auth:api" => [
        "name"        => "API Authentication",
        "description" => "The user must be authenticated for API use",
    ],

    'auth.basic' => [
        "name"        => "Basic Authentication",
        "description" => "The User needs to be authenticated with Basic Auth"
    ],

    'can' => [
        "name"        => "Authorized",
        "description" => "The User needs to be authorized to access this resource",
    ],

    'guest' => [
        "name"        => "Guest",
        "description" => "Available only for unauthencticated Users",
    ],

    "throttle" => [
        "name"        => "Rate limit",
        "description" => "Number of requests per minute must not exceed the predefined treshold (60 requests per second)",
    ],

    "Illuminate\Session\Middleware\StartSession" => [
        "name"        => "Sessions",
        "description" => "Uses cookie based sessions",
    ],

    "Illuminate\Auth\Middleware\Authenticate" => [
        "name"        => "Authentication",
        "description" => "Requires for the user to be authenticated",
    ],

    "Illuminate\Routing\Middleware\ThrottleRequests" => [
        "name"        => "Rate limit",
        "description" => "Number of requests per minute must not exceed the predefined treshold (60 requests per second)",
    ],

    "Illuminate\Routing\Middleware\ThrottleRequests:60,1" => [
        "name"        => "Rate limit",
        "description" => "Number of requests per minute must not exceed the predefined treshold (60 requests per second)",
    ],

    "App\Http\Middleware\RedirectIfAuthenticated" => [
        "name"        => "Unautheticated users only",
        "description" => "This route is availble only for unauthenticated Users",
    ],

    "Illuminate\Auth\Middleware\Authenticate:api" => [
        "name"        => "API Authentication",
        "description" => "The user must be authenticated for API use",
    ],

    "web"                                                     => [],
    "api"                                                     => [],
    "App\Http\Middleware\EncryptCookies"                      => [],
    "Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse" => [],
    "Illuminate\View\Middleware\ShareErrorsFromSession"       => [],
    "App\Http\Middleware\VerifyCsrfToken"                     => [],
    "Illuminate\Routing\Middleware\SubstituteBindings"        => [],
    "Laravel\Passport\Http\Middleware\CreateFreshApiToken"    => [],
    "Laravel\Passport\Http\Middleware\CheckClientCredentials" => [],
];