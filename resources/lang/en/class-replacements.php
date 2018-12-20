<?php

return [
    "Laravel\Passport\Http\Controllers\DenyAuthorizationController@deny" => [
        "controller" => [
            "comment" => [
                "text"     => "Class OAuth",
                "tags"     => [
                    [
                        "type"  => "@title",
                        "value" => "OAtuh",
                    ],
                    [
                        "type"  => "@package",
                        "value" => "Laravel\Passport\Http\Controllers",
                    ],
                ],
                "original" => '',
            ],
        ],
        'function' => [
            'name' => 'Deny a Client authorization',
            'request' => [
                'class'      => \Illuminate\Http\Request::class,
                'parameters' => [
                    [
                        'attribute'   => '_token',
                        'validations' => [
                            [
                                "text"        => "required",
                                "name"        => "required",
                                "args"        => [],
                                "description" => "The _token field is required.",
                            ],

                            [
                                "text"        => "csrf",
                                "name"        => "csrf",
                                "args"        => [],
                                "description" => "The _token must be valid CSRF token.",
                            ],
                        ],
                    ],

                    [
                        'attribute'   => '_method',
                        'validations' => [
                            [
                                "text"        => "string",
                                "name"        => "string",
                                "args"        => [],
                                "description" => "The _method must be DELETE.",
                            ],
                        ],
                    ],
                    [
                        'attribute'   => 'client_id',
                        'validations' => [
                            [
                                "text"        => "required",
                                "name"        => "required",
                                "args"        => [],
                                "description" => "The client_id field is required.",
                            ],
                            [
                                "text"        => "string",
                                "name"        => "string",
                                "args"        => [],
                                "description" => "The client_id must be a valid client_id (check your oauth_clients table).",
                            ],
                        ],
                    ],

                    [
                        'attribute'   => 'state',
                        'validations' => [
                            [
                                "text"        => "string",
                                "name"        => "string",
                                "args"        => [],
                                "description" => "The state must be something.",
                            ],
                        ],
                    ],
                ],
            ],
            'return'  => [
                [
                    'type'    => 'redirect',
                    'object'  => null,
                    'example' => 'your-app.com?error=access_denied&state=',
                    'text'    => null,
                ],
            ],
        ],
    ],

    "Laravel\Passport\Http\Controllers\ApproveAuthorizationController@approve" => [
        "controller" => [
            "comment" => [
                "text"     => "Class OAuth",
                "tags"     => [
                    [
                        "type"  => "@title",
                        "value" => "OAtuh",
                    ],
                    [
                        "type"  => "@package",
                        "value" => "Laravel\Passport\Http\Controllers",
                    ],
                ],
                "original" => '',
            ],
        ],
        'function' => [
            'name' => 'Authorize a Client',
            'request' => [
                'class'      => \Illuminate\Http\Request::class,
                'parameters' => [
                    [
                        'attribute'   => '_token',
                        'validations' => [
                            [
                                "text"        => "required",
                                "name"        => "required",
                                "args"        => [],
                                "description" => "The _token field is required.",
                            ],

                            [
                                "text"        => "csrf",
                                "name"        => "csrf",
                                "args"        => [],
                                "description" => "The _token must be valid CSRF token.",
                            ],
                        ],
                    ],
                    [
                        'attribute'   => 'client_id',
                        'validations' => [
                            [
                                "text"        => "required",
                                "name"        => "required",
                                "args"        => [],
                                "description" => "The client_id field is required.",
                            ],
                            [
                                "text"        => "string",
                                "name"        => "string",
                                "args"        => [],
                                "description" => "The client_id must be a valid client_id (check your oauth_clients table).",
                            ],
                        ],
                    ],

                    [
                        'attribute'   => 'state',
                        'validations' => [
                            [
                                "text"        => "string",
                                "name"        => "string",
                                "args"        => [],
                                "description" => "The state must be a string.",
                            ],
                        ],
                    ],
                ],
            ],
            'return'  => [
                [
                    'type'    => 'HTTP redirect',
                    'object'  => null,
                    'example' => json_encode([
                        'code' => 'def50200af48f8eae0e4c4bd6d3009ebf0c790e0a5d14378ed9ef29ea133e0ec11cb8eea6e48d2b70fe954a0d6486c326a694e189d1ca1b35cf16f8244e3297147c74861145f701b785abee76744ae5017b834ea8b75c2995b410a97fd9b01338639d5cddda6e38e4f84480ce329c44f3fab26e34b54561be8d3dca6bc65399111f001cc8627f3469752e7467f74a77a8f7a54ef9a7625419ad4b1f4bc646510df5b2117746589780abbad58e2a26ca0c79c0606b1afa10f1c16fbbb78f53609b088e7288f986d85d1aa12d181101bd846b92cc6cf198a1581c66befac2a52177f40c3f65cd8a99a9fef791a8e9f7a58e0da792c2a499ef59563ea7e78dbb99faa9e16b091fe9775ab1fa0e2a15639fcc5a53a722bc5f389717ea6785252243cad719a92c0957986bd6af8bbf5e1378c071dba0bcedcabe55f6f51be3a7c4b6e725c3f6cd6ea0d5a6f532c178b5588095ca89a2f4cdba6d5ab955da7',
                    ], JSON_PRETTY_PRINT),
                    'text'    => null,
                ],
            ],
        ],
    ],
    "Laravel\Passport\Http\Controllers\AuthorizationController@authorize"      => [
        "controller" => [
            "comment" => [
                "text"     => "Class OAuth",
                "tags"     => [
                    [
                        "type"  => "@title",
                        "value" => "OAtuh",
                    ],
                    [
                        "type"  => "@package",
                        "value" => "Laravel\Passport\Http\Controllers",
                    ],
                ],
                "original" => '',
            ],
        ],

        'function' => [
            'name' => 'Client authorization page',
            'request' => [
                'class'      => \Illuminate\Http\Request::class,
                'parameters' => [
                    [
                        'attribute'   => 'client_id',
                        'validations' => [
                            [
                                "text"        => "required",
                                "name"        => "required",
                                "args"        => [],
                                "description" => "The client_id field is required.",
                            ],

                            [
                                "text"        => "string",
                                "name"        => "string",
                                "args"        => [],
                                "description" => "The client_id must be valid.",
                            ],
                        ],
                    ],
                    [
                        'attribute'   => 'redirect_uri',
                        'validations' => [
                            [
                                "text"        => "required",
                                "name"        => "required",
                                "args"        => [],
                                "description" => "The redirect_uri field is required.",
                            ],
                            [
                                "text"        => "required",
                                "name"        => "required",
                                "args"        => [],
                                "description" => "The redirect_uri must be a valid redirect_uri (check your oauth_clients table).",
                            ],
                        ],
                    ],
                    [
                        'attribute'   => 'response_code',
                        'validations' => [
                            [
                                "text"        => "required",
                                "name"        => "required",
                                "args"        => [],
                                "description" => "The response_code field is required.",
                            ],
                            [
                                "text"        => "string",
                                "name"        => "string",
                                "args"        => [],
                                "description" => "The response_code must be 'code'.",
                            ],
                        ],
                    ],
                ],
            ],
            'return'  => [
                [
                    'type'    => 'HTML page',
                    'object'  => null,
                    'example' => null,
                    'text'    => null,
                ],
            ],
        ],
    ],

    "Laravel\Passport\Http\Controllers\AuthorizedAccessTokenController@destroy" => [
        "controller" => [
            "comment" => [
                "text"     => "Class OAuth",
                "tags"     => [
                    [
                        "type"  => "@title",
                        "value" => "OAtuh",
                    ],
                    [
                        "type"  => "@package",
                        "value" => "Laravel\Passport\Http\Controllerse",
                    ],
                ],
                "original" => '',
            ],
        ],
        'function' => [
            'name' => 'Delete a token',

            'request' => [
                'class'      => \Illuminate\Http\Request::class,
                'parameters' => [],
            ],
            'return'  => [
                [
                    'type'    => 'void',
                    'object'  => null,
                    'example' => null,
                    'text'    => null,
                ],
            ],
        ],
    ],
    "Laravel\Passport\Http\Controllers\AuthorizedAccessTokenController@forUser" => [
        "controller" => [
            "comment" => [
                "text"     => "Class OAuth",
                "tags"     => [
                    [
                        "type"  => "@title",
                        "value" => "OAtuh",
                    ],
                    [
                        "type"  => "@package",
                        "value" => "Laravel\Passport\Http\Controllerse",
                    ],
                ],
                "original" => '',
            ],
        ],
        'function' => [
            'name' => 'Authorized tokens for the authenticated user',
            'request' => [
                'class'      => \Illuminate\Http\Request::class,
                'parameters' => [],
            ],
            'return'  => [
                [
                    'type'    => 'array',
                    'object'  => null,
                    'example' => json_encode([
                        [
                            "id"         => "c146e1180d864983b25ce026b5047849ca2a98a36f0b95fb603298593e0c9b78240b5792b2b0bd7f",
                            "user_id"    => 1,
                            "client_id"  => 1,
                            "name"       => "ttt",
                            "scopes"     => [
                                "place-orders",
                                "check-status",
                            ],
                            "revoked"    => false,
                            "created_at" => "2018-12-13 14:19:51",
                            "updated_at" => "2018-12-13 14:19:51",
                            "expires_at" => "2019-12-13 14:19:51",
                            "client"     => [
                                "id"                     => 1,
                                "user_id"                => 1,
                                "name"                   => "example",
                                "redirect"               => "http://example.local/callback",
                                "personal_access_client" => false,
                                "password_client"        => false,
                                "revoked"                => false,
                                "created_at"             => "2018-12-13 15:49:17",
                                "updated_at"             => "2018-12-13 15:49:17",
                            ],
                        ],
                        [
                            "id"         => "c146e1180d864983b25ce026b5047849ca2a98a36f0b95fb603298593e0c9b78240b5792b2b0bd7f",
                            "user_id"    => 1,
                            "client_id"  => 1,
                            "name"       => "ttt",
                            "scopes"     => [
                                "place-orders",
                                "check-status",
                            ],
                            "revoked"    => false,
                            "created_at" => "2018-12-13 14:19:51",
                            "updated_at" => "2018-12-13 14:19:51",
                            "expires_at" => "2019-12-13 14:19:51",
                            "client"     => [
                                "id"                     => 1,
                                "user_id"                => 1,
                                "name"                   => "example",
                                "redirect"               => "http://example.local/callback",
                                "personal_access_client" => false,
                                "password_client"        => false,
                                "revoked"                => false,
                                "created_at"             => "2018-12-13 15:49:17",
                                "updated_at"             => "2018-12-13 15:49:17",
                            ],
                        ],
                    ], JSON_PRETTY_PRINT),
                    'text'    => null,
                ],
            ],
        ],
    ],

    "Laravel\Passport\Http\Controllers\TransientTokenController@refresh" => [
        "controller" => [
            "comment" => [
                "text"     => "Class OAuth",
                "tags"     => [
                    [
                        "type"  => "@title",
                        "value" => "OAtuh",
                    ],
                    [
                        "type"  => "@package",
                        "value" => "Laravel\Passport\Http\Controllerse",
                    ],
                ],
                "original" => '',
            ],
        ],
        'function' => [
            'name' => 'Refresh a token',
            'request' => [
                'class'      => \Illuminate\Http\Request::class,
                'parameters' => [
                    [
                        'attribute'   => 'client_id',
                        'validations' => [
                            [
                                "text"        => "required",
                                "name"        => "required",
                                "args"        => [],
                                "description" => "The client_id field is required.",
                            ],

                            [
                                "text"        => "string",
                                "name"        => "string",
                                "args"        => [],
                                "description" => "The client_id must be valid.",
                            ],
                        ],
                    ],
                    [
                        'attribute'   => 'client_secret',
                        'validations' => [
                            [
                                "text"        => "required",
                                "name"        => "required",
                                "args"        => [],
                                "description" => "The client_secret field is required.",
                            ],

                            [
                                "text"        => "required",
                                "name"        => "required",
                                "args"        => [],
                                "description" => "The client_secret must be valid.",
                            ],
                        ],
                    ],

                    [
                        'attribute'   => 'refresh_token',
                        'validations' => [
                            [
                                "text"        => "required",
                                "name"        => "required",
                                "args"        => [],
                                "description" => "The refresh_token field is required.",
                            ],

                            [
                                "text"        => "required",
                                "name"        => "required",
                                "args"        => [],
                                "description" => "The refresh_token must be a valid refresh token.",
                            ],
                        ],
                    ],

                    [
                        'attribute'   => 'grant_type',
                        'validations' => [

                            [
                                "text"        => "required",
                                "name"        => "required",
                                "args"        => [],
                                "description" => "The grant_type field is required.",
                            ],

                            [
                                "text"        => "string",
                                "name"        => "string",
                                "args"        => [],
                                "description" => "The grant_type field must be refresh_token.",
                            ],
                        ],
                    ],

                    [
                        'attribute'   => 'scope',
                        'validations' => [
                            [
                                "text"        => "string",
                                "name"        => "string",
                                "args"        => [],
                                "description" => "Must be a valid scope name.",
                            ],
                        ],
                    ],
                ],
            ],
            'return'  => [
                [
                    'type'    => 'object',
                    'object'  => null,
                    'example' => json_encode([
                        "token_type"    => "Bearer",
                        "expires_in"    => 1296000,
                        "access_token"  => "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6Ijg2MjYwNTk0YzU5M2UxNDc5OGQwNmZjMjZmN2Y1NWQyNjMzMWJiNjI2ZDI5NzU0MWRhZjVhZGU1YTM1NzIzNTgyN2Q5ODZhZjUzNmQ4MjcxIn0.eyJhdWQiOiI1Yjk3OGVjYjlhODkyMDEwNjYzZDQ5MzIiLCJqdGkiOiI4NjI2MDU5NGM1OTNlMTQ3OThkMDZmYzI2ZjdmNTVkMjYzMzFiYjYyNmQyOTc1NDFkYWY1YWRlNWEzNTcyMzU4MjdkOTg2YWY1MzZkODI3MSIsImlhdCI6MTU0NDcxMjA3NCwibmJmIjoxNTQ0NzEyMDc0LCJleHAiOjE1NDYwMDgwNzQsInN1YiI6IjU5MWM5NzgzOWE4OTIwMjNmZjc5NTBkMiIsInNjb3BlcyI6W119.avfitFfHwrqJSwyLUt0U29lMO1gNBYsnAa0vwl-IEaQgT10gqSeNxC-9fAk_z0ZA_Dc9YvVcA8B2jOJzvnwO7Lrl2aczh-UP-ee7ViIqTFStXtsPEh5JIomr2zVZox-DJA1L5KAJ0SxUVYTGaiMQcjpzYvW6bcQKLQc0SHUSRlImDnFTo8FF-7_7Gj8g0ydHQ3CGZPn22IY64X8eG4AFwj-4txxEBGCJH_ifplUQQ-hQYq91oCqQ0JBa5kTJIr1l1dcRSyZktWaFkXmDMlqaKHVeX1PQyHe8Hfd72UjNmg_6PCHfb6jaFUrI_kOqhK_bJIhI1jK8GKHcBjSDLnTL5THBGa_jeiOyNuroVleBseLLaWvO_UikFqNGjTk5YypDIfZqY9tD2eq9x0k1ftHX_168Z6M5g6aUGRQFdrUeeT-L78zLjB9Vz0PK_0f_wK7TeGuRkGh_slxSTCV9szv_m-Qu_str8xYIgj3D2xQX10OGBCMQKx83KvcGadA08K7hUY2MrnqfcDFEIPAfjrgtwv83tZRS72iW0eHZwdpR3ODVUy2LT_RI-5VNZPpKf0BeQz6_8wIVS7RckCQWDU8jas5Tm7Gf2q52wGmdmhXG7olYfnJVw44rZAM6uk9t_yukM7t7P-Ca3TiJIzJLZTLjAd2AjT-aktQMObeHDo2Vhac",
                        "refresh_token" => "def50200e37ca62c58fa41540f4efdfd476949b21641a54b27b2146f054e383db3766c15f170acb3abdd11e6384e51b3f81116b95c19fc60902adf101414e07c75e0b9297768fb11a28fd2802cae74dd8c85b750d3fe153c482cc7fb872ab20d9bbdcdd6d5db0f3a567696246739919d6f2a24ce3932537b6342d0d418f158376195d23dde200e190adc5454accb04b1cfe11196de3918c1a07bd023a4fa2b337da2ee9503affe7812e395ae2d94a9a44fcad2e9cf196c2ccf31ca5efdb1b09d22b1bd46b2310f78c2b0cf747df50fb290e07bcf003cf39d1c7e7d9881b89cd2e9841b8ce7e944b39364d7581497459cd177f31f8ae526b29b9f1ce817fa8f36262340d65eff438e9fb560eab84434c4116a56515518e2f94449ff602641b95a2c0aeb58bb1fa630ae6ef20d84e03e6c99268acb1ec3a9d3e001fb29a869ad592b803362734cfc21cae8c0e80bf0ba1938658570201cab83b29b88d3c178e2d8baf81f6c737cb966ee7c0d37d58e6ae5f94529870733d125abf5a45844b2284699a01a9a3a5bd36db218b510f8eafea5cf",
                    ], JSON_PRETTY_PRINT),
                    'text'    => null,
                ],
            ],
        ],
    ],

    "Laravel\Passport\Http\Controllers\AccessTokenController@issueToken" => [
        "controller" => [
            "comment" => [
                "text"     => "Class OAuth",
                "tags"     => [
                    [
                        "type"  => "@title",
                        "value" => "OAtuh",
                    ],
                    [
                        "type"  => "@package",
                        "value" => "Laravel\Passport\Http\Controllerse",
                    ],
                ],
                "original" => '',
            ],
        ],
        'function' => [
            'name' => 'Issue a Token',
            'request' => [
                'class'      => \Illuminate\Http\Request::class,
                'parameters' => [
                    [
                        'attribute'   => 'client_id',
                        'validations' => [
                            [
                                "text"        => "required",
                                "name"        => "required",
                                "args"        => [],
                                "description" => "The client_id field is required.",
                            ],

                            [
                                "text"        => "string",
                                "name"        => "string",
                                "args"        => [],
                                "description" => "The client_id must be valid.",
                            ],
                        ],
                    ],
                    [
                        'attribute'   => 'client_secret',
                        'validations' => [
                            [
                                "text"        => "required",
                                "name"        => "required",
                                "args"        => [],
                                "description" => "The client_secret field is required.",
                            ],

                            [
                                "text"        => "required",
                                "name"        => "required",
                                "args"        => [],
                                "description" => "The client_secret must be valid.",
                            ],
                        ],
                    ],

                    [
                        'attribute'   => 'grant_type',
                        'validations' => [

                            [
                                "text"        => "required",
                                "name"        => "required",
                                "args"        => [],
                                "description" => "The grant_type field is required.",
                            ],

                            [
                                "text"        => "string",
                                "name"        => "string",
                                "args"        => [],
                                "description" => "The grant_type field must be a valid grant type.",
                            ],
                        ],
                    ],

                    [
                        'attribute'   => 'redirect_uri',
                        'validations' => [

                            [
                                "text"        => "required",
                                "name"        => "required",
                                "args"        => [],
                                "description" => "The redirect_uri field must be valid (check your oauth_clients table).",
                            ],
                        ],
                    ],

                    [
                        'attribute'   => 'username',
                        'validations' => [
                            [
                                "text"        => "string",
                                "name"        => "string",
                                "args"        => [],
                                "description" => "The username must be a valid username.",
                            ],
                        ],
                    ],

                    [
                        'attribute'   => 'password',
                        'validations' => [
                            [
                                "text"        => "password",
                                "name"        => "password",
                                "args"        => [],
                                "description" => "The password must be a valid password.",
                            ],
                        ],
                    ],

                    [
                        'attribute'   => 'scope',
                        'validations' => [
                            [
                                "text"        => "string",
                                "name"        => "string",
                                "args"        => [],
                                "description" => "Must be a valid scope name.",
                            ],
                        ],
                    ],
                ],
            ],
            'return'  => [
                [
                    'type'    => 'object',
                    'object'  => null,
                    'example' => json_encode([
                        "token_type"    => "Bearer",
                        "expires_in"    => 1296000,
                        "access_token"  => "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6Ijg2MjYwNTk0YzU5M2UxNDc5OGQwNmZjMjZmN2Y1NWQyNjMzMWJiNjI2ZDI5NzU0MWRhZjVhZGU1YTM1NzIzNTgyN2Q5ODZhZjUzNmQ4MjcxIn0.eyJhdWQiOiI1Yjk3OGVjYjlhODkyMDEwNjYzZDQ5MzIiLCJqdGkiOiI4NjI2MDU5NGM1OTNlMTQ3OThkMDZmYzI2ZjdmNTVkMjYzMzFiYjYyNmQyOTc1NDFkYWY1YWRlNWEzNTcyMzU4MjdkOTg2YWY1MzZkODI3MSIsImlhdCI6MTU0NDcxMjA3NCwibmJmIjoxNTQ0NzEyMDc0LCJleHAiOjE1NDYwMDgwNzQsInN1YiI6IjU5MWM5NzgzOWE4OTIwMjNmZjc5NTBkMiIsInNjb3BlcyI6W119.avfitFfHwrqJSwyLUt0U29lMO1gNBYsnAa0vwl-IEaQgT10gqSeNxC-9fAk_z0ZA_Dc9YvVcA8B2jOJzvnwO7Lrl2aczh-UP-ee7ViIqTFStXtsPEh5JIomr2zVZox-DJA1L5KAJ0SxUVYTGaiMQcjpzYvW6bcQKLQc0SHUSRlImDnFTo8FF-7_7Gj8g0ydHQ3CGZPn22IY64X8eG4AFwj-4txxEBGCJH_ifplUQQ-hQYq91oCqQ0JBa5kTJIr1l1dcRSyZktWaFkXmDMlqaKHVeX1PQyHe8Hfd72UjNmg_6PCHfb6jaFUrI_kOqhK_bJIhI1jK8GKHcBjSDLnTL5THBGa_jeiOyNuroVleBseLLaWvO_UikFqNGjTk5YypDIfZqY9tD2eq9x0k1ftHX_168Z6M5g6aUGRQFdrUeeT-L78zLjB9Vz0PK_0f_wK7TeGuRkGh_slxSTCV9szv_m-Qu_str8xYIgj3D2xQX10OGBCMQKx83KvcGadA08K7hUY2MrnqfcDFEIPAfjrgtwv83tZRS72iW0eHZwdpR3ODVUy2LT_RI-5VNZPpKf0BeQz6_8wIVS7RckCQWDU8jas5Tm7Gf2q52wGmdmhXG7olYfnJVw44rZAM6uk9t_yukM7t7P-Ca3TiJIzJLZTLjAd2AjT-aktQMObeHDo2Vhac",
                        "refresh_token" => "def50200e37ca62c58fa41540f4efdfd476949b21641a54b27b2146f054e383db3766c15f170acb3abdd11e6384e51b3f81116b95c19fc60902adf101414e07c75e0b9297768fb11a28fd2802cae74dd8c85b750d3fe153c482cc7fb872ab20d9bbdcdd6d5db0f3a567696246739919d6f2a24ce3932537b6342d0d418f158376195d23dde200e190adc5454accb04b1cfe11196de3918c1a07bd023a4fa2b337da2ee9503affe7812e395ae2d94a9a44fcad2e9cf196c2ccf31ca5efdb1b09d22b1bd46b2310f78c2b0cf747df50fb290e07bcf003cf39d1c7e7d9881b89cd2e9841b8ce7e944b39364d7581497459cd177f31f8ae526b29b9f1ce817fa8f36262340d65eff438e9fb560eab84434c4116a56515518e2f94449ff602641b95a2c0aeb58bb1fa630ae6ef20d84e03e6c99268acb1ec3a9d3e001fb29a869ad592b803362734cfc21cae8c0e80bf0ba1938658570201cab83b29b88d3c178e2d8baf81f6c737cb966ee7c0d37d58e6ae5f94529870733d125abf5a45844b2284699a01a9a3a5bd36db218b510f8eafea5cf",
                    ], JSON_PRETTY_PRINT),
                    'text'    => null,
                ],
            ],
        ],
    ],

    "Laravel\Passport\Http\Controllers\ScopeController@all"                   => [
        "controller" => [
            "comment" => [
                "text"     => "Class OAuth",
                "tags"     => [
                    [
                        "type"  => "@title",
                        "value" => "OAtuh",
                    ],
                    [
                        "type"  => "@package",
                        "value" => "Laravel\Passport\Http\Controllerse",
                    ],
                ],
                "original" => '',
            ],
        ],
        'function' => [
            'name' => 'Get scopes',
            'request' => [
                'class'      => \Illuminate\Http\Request::class,
                'parameters' => [],
            ],
            'return'  => [
                [
                    'type'    => 'array',
                    'object'  => null,
                    'example' => json_encode([
                        [
                            "id"          => "place-orders",
                            "description" => "Place orders",
                        ],
                        [
                            "id"          => "check-status",
                            "description" => "Check order status",
                        ],
                    ], JSON_PRETTY_PRINT),
                    'text'    => null,
                ],
            ],
        ],
    ],
    "Laravel\Passport\Http\Controllers\PersonalAccessTokenController@destroy" => [
        "controller" => [
            "comment" => [
                "text"     => "Class OAuth",
                "tags"     => [
                    [
                        "type"  => "@title",
                        "value" => "OAtuh",
                    ],
                    [
                        "type"  => "@package",
                        "value" => "Laravel\Passport\Http\Controllerse",
                    ],
                ],
                "original" => '',
            ],
        ],
        'function' => [
            'name' => 'Delete a personal access token',
            'request' => [
                'class'      => \Illuminate\Http\Request::class,
                'parameters' => [],
            ],
            'return'  => [
                [
                    'type'    => 'void',
                    'object'  => null,
                    'example' => null,
                    'text'    => null,
                ],
            ],
        ],
    ],
    "Laravel\Passport\Http\Controllers\PersonalAccessTokenController@forUser" => [
        "controller" => [
            "comment" => [
                "text"     => "Class OAuth",
                "tags"     => [
                    [
                        "type"  => "@title",
                        "value" => "OAtuh",
                    ],
                    [
                        "type"  => "@package",
                        "value" => "Laravel\Passport\Http\Controllerse",
                    ],
                ],
                "original" => '',
            ],
        ],
        'function' => [
            'name' => 'All personal access tokens for the authenticated user.',
            'request' => [
                'class'      => \Illuminate\Http\Request::class,
                'parameters' => [],
            ],
            'return'  => [
                [
                    'type'    => 'array',
                    'object'  => null,
                    'example' => json_encode([
                        [
                            "id"         => "15a9eceb263fd62f02afe5806801e16efe0fbf911b89fca840a3f72502a26150c5d9ede2a197f3c4",
                            "user_id"    => 1,
                            "client_id"  => 1,
                            "name"       => "dsads",
                            "scopes"     => [],
                            "revoked"    => false,
                            "created_at" => "2018-12-12 18:13:09",
                            "updated_at" => "2018-12-12 18:13:09",
                            "expires_at" => "2019-12-12 18:13:09",
                            "client"     => [
                                "id"                     => 1,
                                "user_id"                => null,
                                "name"                   => "Novo Personal Access Client",
                                "redirect"               => "http://localhost",
                                "personal_access_client" => true,
                                "password_client"        => false,
                                "revoked"                => false,
                                "created_at"             => "2018-12-12 17:30:02",
                                "updated_at"             => "2018-12-12 17:30:02",
                            ],
                        ],
                        [
                            "id"         => "7fe121055c7625fb81a5fb6dbb8a9a20a429963e5bfafaf19aba95eafb5873e864119237d98bf256",
                            "user_id"    => 1,
                            "client_id"  => 1,
                            "name"       => "MRK",
                            "scopes"     => [],
                            "revoked"    => false,
                            "created_at" => "2018-12-12 18:08:16",
                            "updated_at" => "2018-12-12 18:08:16",
                            "expires_at" => "2019-12-12 18:08:16",
                            "client"     => [
                                "id"                     => 1,
                                "user_id"                => null,
                                "name"                   => "Novo Personal Access Client",
                                "redirect"               => "http://localhost",
                                "personal_access_client" => true,
                                "password_client"        => false,
                                "revoked"                => false,
                                "created_at"             => "2018-12-12 17:30:02",
                                "updated_at"             => "2018-12-12 17:30:02",
                            ],
                        ],
                    ], JSON_PRETTY_PRINT),
                    'text'    => null,
                ],
            ],
        ],
    ],
    "Laravel\Passport\Http\Controllers\PersonalAccessTokenController@store"   => [
        "controller" => [
            "comment" => [
                "text"     => "Class OAuth",
                "tags"     => [
                    [
                        "type"  => "@title",
                        "value" => "OAtuh",
                    ],
                    [
                        "type"  => "@package",
                        "value" => "Laravel\Passport\Http\Controllerse",
                    ],
                ],
                "original" => '',
            ],
        ],
        'function' => [
            'name' => 'Create a personal access token',
            'request' => [
                'class'      => \Illuminate\Http\Request::class,
                'parameters' => [
                    [
                        'attribute'   => 'name',
                        'validations' => [
                            [
                                "text"        => "required",
                                "name"        => "required",
                                "args"        => [],
                                "description" => "The name field is required.",
                            ],

                            [
                                "text"        => "string",
                                "name"        => "string",
                                "args"        => [],
                                "description" => "The name field must be a string.",
                            ],
                        ],
                    ],

                    [
                        'attribute'   => 'scopes',
                        'validations' => [
                            [
                                "text"        => "array",
                                "name"        => "array",
                                "args"        => [],
                                "description" => "The scopes must be an array.",
                            ],
                        ],
                    ],

                    [
                        'attribute'   => 'scopes.*',
                        'validations' => [
                            [
                                "text"        => "array",
                                "name"        => "array",
                                "args"        => [],
                                "description" => "Must be a valid scope name.",
                            ],
                        ],
                    ],
                ],
            ],
            'return'  => [
                [
                    'type'    => 'object',
                    'object'  => null,
                    'example' => json_encode([
                        "accessToken" => "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjdmZTEyMTA1NWM3NjI1ZmI4MWE1ZmI2ZGJiOGE5YTIwYTQyOTk2M2U1YmZhZmFmMTlhYmE5NWVhZmI1ODczZTg2NDExOTIzN2Q5OGJmMjU2In0.eyJhdWQiOiIxIiwianRpIjoiN2ZlMTIxMDU1Yzc2MjVmYjgxYTVmYjZkYmI4YTlhMjBhNDI5OTYzZTViZmFmYWYxOWFiYTk1ZWFmYjU4NzNlODY0MTE5MjM3ZDk4YmYyNTYiLCJpYXQiOjE1NDQ2MzgwOTYsIm5iZiI6MTU0NDYzODA5NiwiZXhwIjoxNTc2MTc0MDk2LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.jjo7IvCbFD6MGi_fauq5GeUsKyOUsH4wYw-MCZxm-ZLh7De4YUYPqH-ZDpIB9xB0nkHJ4Q8gJknHLMibR5zwOxsp2qGgrsF5ZMi18e5MAUY7167ukPorNs8ZWGhfhPZS1G9_Z82s_U8tsVAVCQjAdjDyn9OnNmDnVFry1UFBfa58RS5zvNDHCVN4nprAXKu5L_iCmf_tYqcKG3aeHkCLgJoQvLklNSEZhfeEqMpTvRCRwBKT0wZuwRFwTVsR_rJuF9ixJMAr8NaUPdIFEwFWQlYJiv8aH5VfwrGBRDu8I93ajKptlIoIcf-ZJFQjL1CVV_ApydPuJTQkrIKuKphQy9Xp4sqykBJjfXi_SVkfAy-tebYleQA6gsmvHXenbhDRdx2H5n4lzgRqnDj09o8kJgveV3P5HkuEYCuH8V-QxquyNFjJKgEw8geUpWtLITmhZeXvN1U_dutSsxnY2GvLPgvG2WIqXZMV168qQq3U0xFAhF5ERETuqjWI4jA1mrgl5LOlgvoAYf8LIfT8aETXus21wJEvFOGgnTfwwNKR5rK8ZLvSVoCPTaLgTMcrsfwku9vWz_VJpgpRUXCoD4UlLkfgpyApsKSEKXyoHmvVdoPaNoOoif-Jc4FPpGL7exPZI_OhB3HAfAQCvlj3673ODYh7htEddHo6HCmylgAltmk",
                        "token"       => [
                            "id"         => "7fe121055c7625fb81a5fb6dbb8a9a20a429963e5bfafaf19aba95eafb5873e864119237d98bf256",
                            "user_id"    => 1,
                            "client_id"  => 1,
                            "name"       => "MRK",
                            "scopes"     => [],
                            "revoked"    => false,
                            "created_at" => "2018-12-12 18:08:16",
                            "updated_at" => "2018-12-12 18:08:16",
                            "expires_at" => "2019-12-12 18:08:16",
                        ],
                    ], JSON_PRETTY_PRINT),
                    'text'    => null,
                ],
            ],
        ],
    ],

    "Laravel\Passport\Http\Controllers\ClientController@forUser" => [
        "controller" => [
            "comment" => [
                "text"     => "Class OAuth",
                "tags"     => [
                    [
                        "type"  => "@title",
                        "value" => "OAtuh",
                    ],
                    [
                        "type"  => "@package",
                        "value" => "Laravel\Passport\Http\Controllerse",
                    ],
                ],
                "original" => '',
            ],
        ],
        'function' => [
            'name' => 'List users clients',
            'request' => [
                'class'      => \Illuminate\Http\Request::class,
                'parameters' => [

                ],
            ],
            'return'  => [
                [
                    'type'    => 'array',
                    'object'  => null,
                    'example' => json_encode([
                        [
                            "id"                     => 3,
                            "user_id"                => 1,
                            "name"                   => "New client",
                            "secret"                 => "bxFv7lUTBx8HhS05S4CavkthP6EQIk5wgegkD7It",
                            "redirect"               => "http://example.local/callback",
                            "personal_access_client" => false,
                            "password_client"        => false,
                            "revoked"                => false,
                            "created_at"             => "2018-12-12 17:41:39",
                            "updated_at"             => "2018-12-12 17:41:39",
                        ],
                        [
                            "id"                     => 4,
                            "user_id"                => 1,
                            "name"                   => "New client 2",
                            "secret"                 => "bxFv7lUTBx8HhS05S4CavkthP6EQIk5wgegkD7It",
                            "redirect"               => "http://example.local/callback",
                            "personal_access_client" => false,
                            "password_client"        => false,
                            "revoked"                => false,
                            "created_at"             => "2018-12-12 17:41:55",
                            "updated_at"             => "2018-12-12 17:41:55",
                        ],
                    ], JSON_PRETTY_PRINT),
                    'text'    => null,
                ],
            ],
        ],
    ],

    "Laravel\Passport\Http\Controllers\ClientController@update" => [
        "controller" => [
            "comment" => [
                "text"     => "Class OAuth",
                "tags"     => [
                    [
                        "type"  => "@title",
                        "value" => "OAtuh",
                    ],
                    [
                        "type"  => "@package",
                        "value" => "Laravel\Passport\Http\Controllerse",
                    ],
                ],
                "original" => '',
            ],
        ],
        'function' => [
            'name' => 'Update a Client',
            'request' => [
                'class'      => \Illuminate\Http\Request::class,
                'parameters' => [
                    [
                        'attribute'   => 'name',
                        'validations' => [
                            [
                                "text"        => "required",
                                "name"        => "required",
                                "args"        => [],
                                "description" => "The name field is required.",
                            ],

                            [
                                "text"        => "string",
                                "name"        => "string",
                                "args"        => [],
                                "description" => "The name field must be a string.",
                            ],
                        ],
                    ],
                    [
                        'attribute'   => 'redirect',
                        'validations' => [
                            [
                                "text"        => "required",
                                "name"        => "required",
                                "args"        => [],
                                "description" => "The redirect field is required.",
                            ],
                            [
                                "text"        => "url",
                                "name"        => "url",
                                "args"        => [],
                                "description" => "The redirect must be a valid URL.",
                            ],
                        ],
                    ],
                ],

            ],
            'return'  => [
                [
                    'type'    => 'object',
                    'object'  => null,
                    'example' => json_encode([
                        'created_at'             => "2018-12-12 17:41:39",
                        'id'                     => 3,
                        'name'                   => "New client",
                        'password_client'        => false,
                        'personal_access_client' => false,
                        'redirect'               => "http://novo.local/callback",
                        'revoked'                => false,
                        'secret'                 => "bxFv7lUTBx8HhS05S4CavkthP6EQIk5wgegkD7It",
                        'updated_at'             => "2018-12-12 17:41:39",
                        'user_id'                => 1,
                    ], JSON_PRETTY_PRINT),
                    'text'    => null,
                ],
            ],
        ],
    ],

    "Laravel\Passport\Http\Controllers\ClientController@store" => [
        "controller" => [
            "comment" => [
                "text"     => "Class OAuth",
                "tags"     => [
                    [
                        "type"  => "@title",
                        "value" => "OAtuh",
                    ],
                    [
                        "type"  => "@package",
                        "value" => "Laravel\Passport\Http\Controllerse",
                    ],
                ],
                "original" => '',
            ],
        ],
        'function' => [
            'name' => 'Store a new client',
            'request' => [
                'class'      => \Illuminate\Http\Request::class,
                'parameters' => [
                    [
                        'attribute'   => 'name',
                        'validations' => [
                            [
                                "text"        => "required",
                                "name"        => "required",
                                "args"        => [],
                                "description" => "The name field is required.",
                            ],

                            [
                                "text"        => "string",
                                "name"        => "string",
                                "args"        => [],
                                "description" => "The name field must be a string.",
                            ],
                        ],
                    ],
                    [
                        'attribute'   => 'redirect',
                        'validations' => [
                            [
                                "text"        => "required",
                                "name"        => "required",
                                "args"        => [],
                                "description" => "The redirect field is required.",
                            ],
                            [
                                "text"        => "url",
                                "name"        => "url",
                                "args"        => [],
                                "description" => "The redirect must be a valid URL.",
                            ],
                        ],
                    ],
                ],

            ],
            'return'  => [
                [
                    'type'    => 'object',
                    'object'  => null,
                    'example' => json_encode([
                        'created_at'             => "2018-12-12 17:41:39",
                        'id'                     => 3,
                        'name'                   => "New client",
                        'password_client'        => false,
                        'personal_access_client' => false,
                        'redirect'               => "http://novo.local/callback",
                        'revoked'                => false,
                        'secret'                 => "bxFv7lUTBx8HhS05S4CavkthP6EQIk5wgegkD7It",
                        'updated_at'             => "2018-12-12 17:41:39",
                        'user_id'                => 1,
                    ], JSON_PRETTY_PRINT),
                    'text'    => null,
                ],
            ],
        ],
    ],

    "Laravel\Passport\Http\Controllers\ClientController@destroy" => [
        "controller" => [
            "comment" => [
                "text"     => "Class OAuth",
                "tags"     => [
                    [
                        "type"  => "@title",
                        "value" => "OAtuh",
                    ],
                    [
                        "type"  => "@package",
                        "value" => "Laravel\Passport\Http\Controllerse",
                    ],
                ],
                "original" => '',
            ],
        ],
        'function' => [
            'name' => 'Delete a Client',
            'request' => [
                'class'      => \Illuminate\Http\Request::class,
                'parameters' => [

                ],
            ],
            'return'  => [
                [
                    'type'    => 'void',
                    'object'  => null,
                    'example' => null,
                    'text'    => null,
                ],
            ],
        ],
    ],
];