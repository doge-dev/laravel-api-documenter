<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Class example list
    |--------------------------------------------------------------------------
    |
    | The following language lines contain examples of classes used by the documenter.
    |
    */

    \Illuminate\Pagination\LengthAwarePaginator::class => [
        "current_page"   => 1,
        "data"           => ['. . . YOUR DATA WILL GO IN HERE . . .'],
        "first_page_url" => env('APP_URL') . "?page=1",
        "from"           => 1,
        "last_page"      => 16,
        "last_page_url"  => env('APP_URL') . "?page=16",
        "next_page_url"  => env('APP_URL') . "?page=2",
        "path"           => env('APP_URL'),
        "per_page"       => 15,
        "prev_page_url"  => null,
        "to"             => 15,
        "total"          => 238,
    ],
];
