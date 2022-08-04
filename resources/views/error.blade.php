@extends('layouts.default')

@section('content')

    @if(isset($error))
        <div class="d-flex align-items-center justify-content-center vh-100">
            <div class="text-center">
                <h1 class="display-1 fw-bold">{{ $error['code'] }}</h1>
                <p class="fs-3"> <span class="text-danger">Opps!</span> {{ $error['message'] }}</p>
                <p class="lead">
                    The page you’re looking for doesn't exist.
                </p>
                <h3><a href="{{ route($url) }}" class="btn btn-danger">Try Again</a></h3>
            </div>
        </div>
    @endif

@endsection