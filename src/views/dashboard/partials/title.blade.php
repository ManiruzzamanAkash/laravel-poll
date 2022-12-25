@if (Route::is('poll.index') || Route::is('poll.home'))
    Polls
@elseif(Route::is('poll.create'))
    Create new Poll
@elseif(Route::is('poll.edit'))
    Edit Poll
@endif
| Admin Panel -
{{ config('app.name') }}
