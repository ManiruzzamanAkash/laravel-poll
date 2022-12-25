<div class="page-breadcrumb pl-0 pr-0">
    <div class="row">
        <div class="col-5 align-self-center">
            <h4 class="page-title">
                @if (Route::is('poll.index') || Route::is('poll.home'))
                    Polls
                    <a class="btn btn-info ml-4" href="{{ route('poll.create') }}">
                        Create New poll
                    </a>
                @elseif(Route::is('poll.create'))
                    Create new Poll
                @elseif(Route::is('poll.edit'))
                    Edit Poll
                @endif
            </h4>
        </div>
        <div class="col-7 align-self-center mb-5">
            <div class="d-flex align-items-center justify-content-end">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>

                        @if (Route::is('poll.index') || Route::is('poll.home'))
                            <li class="breadcrumb-item active" aria-current="page">Polls</li>
                        @elseif(Route::is('poll.create'))
                            <li class="breadcrumb-item"><a href="{{ route('poll.index') }}">Polls</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Create Poll</li>
                        @elseif(Route::is('poll.edit'))
                            <li class="breadcrumb-item"><a href="{{ route('poll.index') }}">Polls</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Poll</li>
                        @endif
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
