<?php

$prefix = config('larapoll_config.prefix');


Route::group(['prefix' => 'admin'], function () use ($prefix) {
    Route::group(['namespace' => 'Inani\Larapoll\Http\Controllers', 'prefix' => $prefix, 'middleware' => 'web'], function () {

        $middleware = config('larapoll_config.admin_auth');

        $guard = config('larapoll_config.admin_guard');
        Route::middleware(["$middleware:$guard"])->group(function () {
            Route::get('/admin_polls', ['uses' => 'PollManagerController@home', 'as' => 'poll.home']);
            Route::get('/', ['uses' => 'PollManagerController@index', 'as' => 'poll.index']);
            Route::get('create', ['uses' => 'PollManagerController@create', 'as' => 'poll.create']);
            Route::get('{poll}', ['uses' => 'PollManagerController@edit', 'as' => 'poll.edit']);
            Route::patch('{poll}', ['uses' => 'PollManagerController@update', 'as' => 'poll.update']);
            Route::delete('{poll}', ['uses' => 'PollManagerController@remove', 'as' => 'poll.remove']);
            Route::patch('{poll}/lock', ['uses' => 'PollManagerController@lock', 'as' => 'poll.lock']);
            Route::patch('{poll}/unlock', ['uses' => 'PollManagerController@unlock', 'as' => 'poll.unlock']);
            Route::post('polls_store', ['uses' => 'PollManagerController@store', 'as' => 'poll.store']);
        });
    });
});

Route::post('/vote/polls/{poll}', 'VoteManagerController@vote')->name('poll.vote');
