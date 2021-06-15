<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Enables or Disables Telegramable.
    |--------------------------------------------------------------------------
    |
    | This variable defines Telegramable work. Set true or false to enable or
    | disable notifications to your Telegram.
    |
    */

    'enabled' => true,

    /*
    |--------------------------------------------------------------------------
    | Only allowed Exceptions Classes
    |--------------------------------------------------------------------------
    |
    | Define here your exceptions of Exceptions.
    | Set only Exceptions Classes you want to get notifications from.
    |
    */

    'exceptions_only' => [
//        ErrorException::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Except Exceptions Classes
    |--------------------------------------------------------------------------
    |
    | Define here your exceptions of Exceptions.
    | Set Exceptions Classes you do not want to get notifications from.
    |
    */

    'exceptions_except' => [
//        \Illuminate\Database\QueryException::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Exceptions Traces
    |--------------------------------------------------------------------------
    |
    | This variable defines traces in notifications. Set true or false to enable or
    | disable traces in notifications message.
    |
    */

    'trace' => true,

    /*
    |--------------------------------------------------------------------------
    | Exceptions Trace Depth
    |--------------------------------------------------------------------------
    |
    | Set up how many parts of trace's 'chain' you want to see.
    |
    */

    'trace_depth' => 3,

    /*
    |--------------------------------------------------------------------------
    | Telegram Bot Token
    |--------------------------------------------------------------------------
    |
    | Telegram API Bot token that you can get from @BotFather bot in Telegram.
    |
    */

    'bot_token' => env('TELEGRAM_BOT_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | Telegram User ID
    |--------------------------------------------------------------------------
    |
    | Telegram User ID, your User ID, if you dont know your User ID you can know
    | it in @userinfobot bot in Telegram. Just type something to this bot, it
    | will return you some information about you. User ID will showed as 'Id'
    |
    */

    'user_id' => env('TELEGRAM_USER_ID'),

    /*
    |--------------------------------------------------------------------------
    | App Name
    |--------------------------------------------------------------------------
    |
    | Takes this value form .env file. App name will showed in notification
    | message to you know where this notification from.
    |
    */

    'app_name' => env('APP_NAME', 'Telegramable'),
];
