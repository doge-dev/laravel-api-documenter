<?php

//GET    | oauth/authorize                         | Laravel\Passport\Http\Controllers\AuthorizationController@authorize       | web,auth
//POST   | oauth/authorize                         | Laravel\Passport\Http\Controllers\ApproveAuthorizationController@approve  | web,auth
//DELETE | oauth/authorize                         | Laravel\Passport\Http\Controllers\DenyAuthorizationController@deny        | web,auth

//POST   | oauth/clients                           | Laravel\Passport\Http\Controllers\ClientController@store                  | web,auth
//GET    | oauth/clients                           | Laravel\Passport\Http\Controllers\ClientController@forUser                | web,auth

//PUT    | oauth/clients/{client_id}               | Laravel\Passport\Http\Controllers\ClientController@update                 | web,auth
//DELETE | oauth/clients/{client_id}               | Laravel\Passport\Http\Controllers\ClientController@destroy                | web,auth

//POST   | oauth/personal-access-tokens            | Laravel\Passport\Http\Controllers\PersonalAccessTokenController@store     | web,auth
//GET    | oauth/personal-access-tokens            | Laravel\Passport\Http\Controllers\PersonalAccessTokenController@forUser   | web,auth

//DELETE | oauth/personal-access-tokens/{token_id} | Laravel\Passport\Http\Controllers\PersonalAccessTokenController@destroy   | web,auth

//GET    | auth/scopes                             | Laravel\Passport\Http\Controllers\ScopeController@all                     | web,auth
//POST   | oauth/token                             | Laravel\Passport\Http\Controllers\AccessTokenController@issueToken        | throttle
//POST   | oauth/token/refresh                     | Laravel\Passport\Http\Controllers\TransientTokenController@refresh        | web,auth
//GET    | oauth/tokens                            | Laravel\Passport\Http\Controllers\AuthorizedAccessTokenController@forUser | web,auth
//DELETE | oauth/tokens/{token_id}                 | Laravel\Passport\Http\Controllers\AuthorizedAccessTokenController@destroy | web,auth

return [
    'Laravel\Passport\Http\Controllers\AuthorizationController@authorize'       => (object)[
        'request' => (object)[
            'class' => null,
            'items' => [
                [
                    'attribute'   => 'client_id',
                    'validations' => [
                        'text'        => 'required',
                        'name'        => 'required',
                        'args'        => [],
                        'description' => 'The client_id field is required',
                    ],
                ],
                [
                    'attribute'   => 'redirect_uri',
                    'validations' => [
                        'text'        => 'required',
                        'name'        => 'required',
                        'args'        => [],
                        'description' => 'The redirect_uri field is required',
                    ],
                ],
                [
                    'attribute'   => 'response_type',
                    'validations' => [
                        'text'        => 'required',
                        'name'        => 'required',
                        'args'        => [],
                        'description' => 'The response_type field is required',
                    ],
                ],
                [
                    'attribute'   => 'scope',
                    'validations' => [
                        'text'        => 'required',
                        'name'        => 'required',
                        'args'        => [],
                        'description' => 'The scope field is required',
                    ],
                ],
            ],
        ],
        'return'  => (object)[
            'items' => [
                'type'    => 'object',
                'object'  => null,
                'example' => [

                ],
                'text'    => null,
            ],
        ],
    ],
    "Laravel\Passport\Http\Controllers\AuthorizationController@authorize"       => (object)[
        'request' => (object)[
            'class' => null,
            'items' => [

            ],
        ],
        'return'  => (object)[
            'items' => [
                'type'    => 'object',
                'object'  => null,
                'example' => [

                ],
                'text'    => null,
            ],
        ],
    ],
    "Laravel\Passport\Http\Controllers\ApproveAuthorizationController@approve"  => (object)[
        'request' => (object)[
            'class' => null,
            'items' => [

            ],
        ],
        'return'  => (object)[
            'items' => [
                'type'    => 'object',
                'object'  => null,
                'example' => [

                ],
                'text'    => null,
            ],
        ],
    ],
    "Laravel\Passport\Http\Controllers\DenyAuthorizationController@deny"        => (object)[
        'request' => (object)[
            'class' => null,
            'items' => [

            ],
        ],
        'return'  => (object)[
            'items' => [
                'type'    => 'object',
                'object'  => null,
                'example' => [

                ],
                'text'    => null,
            ],
        ],
    ],
    "Laravel\Passport\Http\Controllers\ClientController@store"                  => (object)[
        'request' => (object)[
            'class' => null,
            'items' => [

            ],
        ],
        'return'  => (object)[
            'items' => [
                'type'    => 'object',
                'object'  => null,
                'example' => [

                ],
                'text'    => null,
            ],
        ],
    ],
    "Laravel\Passport\Http\Controllers\ClientController@forUser"                => (object)[
        'request' => (object)[
            'class' => null,
            'items' => [

            ],
        ],
        'return'  => (object)[
            'items' => [
                'type'    => 'object',
                'object'  => null,
                'example' => [

                ],
                'text'    => null,
            ],
        ],
    ],
    "Laravel\Passport\Http\Controllers\ClientController@update"                 => (object)[
        'request' => (object)[
            'class' => null,
            'items' => [

            ],
        ],
        'return'  => (object)[
            'items' => [
                'type'    => 'object',
                'object'  => null,
                'example' => [

                ],
                'text'    => null,
            ],
        ],
    ],
    "Laravel\Passport\Http\Controllers\ClientController@destroy"                => (object)[
        'request' => (object)[
            'class' => null,
            'items' => [

            ],
        ],
        'return'  => (object)[
            'items' => [
                'type'    => 'object',
                'object'  => null,
                'example' => [

                ],
                'text'    => null,
            ],
        ],
    ],
    "Laravel\Passport\Http\Controllers\PersonalAccessTokenController@store"     => (object)[
        'request' => (object)[
            'class' => null,
            'items' => [

            ],
        ],
        'return'  => (object)[
            'items' => [
                'type'    => 'object',
                'object'  => null,
                'example' => [

                ],
                'text'    => null,
            ],
        ],
    ],
    "Laravel\Passport\Http\Controllers\PersonalAccessTokenController@forUser"   => (object)[
        'request' => (object)[
            'class' => null,
            'items' => [

            ],
        ],
        'return'  => (object)[
            'items' => [
                'type'    => 'object',
                'object'  => null,
                'example' => [

                ],
                'text'    => null,
            ],
        ],
    ],
    "Laravel\Passport\Http\Controllers\PersonalAccessTokenController@destroy"   => (object)[
        'request' => (object)[
            'class' => null,
            'items' => [

            ],
        ],
        'return'  => (object)[
            'items' => [
                'type'    => 'object',
                'object'  => null,
                'example' => [

                ],
                'text'    => null,
            ],
        ],
    ],
    "Laravel\Passport\Http\Controllers\ScopeController@all"                     => (object)[
        'request' => (object)[
            'class' => null,
            'items' => [

            ],
        ],
        'return'  => (object)[
            'items' => [
                'type'    => 'object',
                'object'  => null,
                'example' => [

                ],
                'text'    => null,
            ],
        ],
    ],
    "Laravel\Passport\Http\Controllers\AccessTokenController@issueToken"        => (object)[
        'request' => (object)[
            'class' => null,
            'items' => [

            ],
        ],
        'return'  => (object)[
            'items' => [
                'type'    => 'object',
                'object'  => null,
                'example' => [

                ],
                'text'    => null,
            ],
        ],
    ],
    "Laravel\Passport\Http\Controllers\TransientTokenController@refresh"        => (object)[
        'request' => (object)[
            'class' => null,
            'items' => [

            ],
        ],
        'return'  => (object)[
            'items' => [
                'type'    => 'object',
                'object'  => null,
                'example' => [

                ],
                'text'    => null,
            ],
        ],
    ],
    "Laravel\Passport\Http\Controllers\AuthorizedAccessTokenController@forUser" => (object)[
        'request' => (object)[
            'class' => null,
            'items' => [

            ],
        ],
        'return'  => (object)[
            'items' => [
                'type'    => 'object',
                'object'  => null,
                'example' => [

                ],
                'text'    => null,
            ],
        ],
    ],
    "Laravel\Passport\Http\Controllers\AuthorizedAccessTokenController@destroy" => (object)[
        'request' => (object)[
            'class' => null,
            'items' => [

            ],
        ],
        'return'  => (object)[
            'items' => [
                'type'    => 'object',
                'object'  => null,
                'example' => [

                ],
                'text'    => null,
            ],
        ],
    ],
];