@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><h1>Notifications</h1></div>
                    <div class="card-body">
                        @foreach ($notifications as $notification)
                            <li class="list-group-item d-flex justify-content-between">
                                @if ($notification->type === "App\Notifications\NewReplyAdded")
                                    A new Reply was posted in your question <strong>{{ $notification->data['question']['title'] }}</strong>
                                    <a href="{{ route('questions.show', $notification->data['question']['slug']) }}"
                                        class="btn btn-sm btn-info text-white">View Question</a>
                                @endif
                            </li>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>    
@endsection