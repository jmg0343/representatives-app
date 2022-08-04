@extends('layouts.default')

@section('content')
    <div class="mt-5">
        <div class="row">
            <div class="col text-center">
                <h1>Welcome!</h1>
                <h5>Check out current and upcoming features below</h5>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-sm-4">
                <div class="card text-center">
                    <div class="card-header">Elections</div>
                    <div class="card-body">
                        <h5 class="card-title">Upcoming Elections/Polling Locations</h5>
                        <p class="card-text">Find information about elections as well as how to register and where to vote.</p>
                        <a href="{{ route('elections') }}" class="btn btn-primary">Get Started</a>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card text-center">
                    <div class="card-header">Representatives</div>
                    <div class="card-body">
                        <h5 class="card-title">Get information About Your Representatives</h5>
                        <p class="card-text">Here's where you can find information about who represents you and how to contact them.</p>
                        <a href="{{ route('reps') }}" class="btn btn-primary">Get Started</a>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card text-center">
                    <div class="card-header">Coming Soon!</div>
                    <div class="card-body">
                        <h5 class="card-title">Campaign Donation Information</h5>
                        <p class="card-text">Find information about who's donating to your elected representatives.</p>
                        <!-- <a href="#" class="btn btn-primary">Click Here</a> -->
                        <a href="#" class="btn btn-secondary">Coming Soon</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection