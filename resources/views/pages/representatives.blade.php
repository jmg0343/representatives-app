@extends('layouts.default')

@push('css')
    <link rel="stylesheet" href="{{ secure_asset('css/representatives.css') }}">
@endpush

@section('content')
    <button
            type="button"
            class="btn btn-danger btn-floating btn-lg"
            id="btn-back-to-top"
    >
        <i class="fas fa-arrow-up"></i>
    </button>
    <div class="row">
        @if(empty($repsInfo))
            <h3 class="text-center">Enter your address to see your elected representatives.</h3>
        @else
            <h3 class="text-center">Here's some information about your representatives</h3>
        @endif
        <form
            id="addressForm"
            action="{{ route('reps.find') }}"
            method="POST"
            style="@if(isset($repsInfo)) display: none; @endif"
        >
            @csrf
            <div class="mb-3">
                <label for="address" class="form-label">Street Address</label>
                <input type="text" class="form-control" id="address" name="address" aria-describedby="address" required>
            </div>
            <div class="row">
                <div class="mb-3 col-sm-6">
                    <label for="city" class="form-label">City</label>
                    <input type="text" class="form-control" id="city" name="city" aria-describedby="city" required>
                </div>
                <div class="mb-3 col-sm-6">
                    <label class="form-label" for="state">State</label>
                    <select class="form-select form-control" name="state" id="state" aria-label="State" required>
                        <option selected disabled>Select State</option>
                        @if(isset($states))
                            @foreach($states as $key => $state)
                                <option value={{ $key }}>{{ $state }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <br>
    <br>
    <div class="row" style="@if(empty($repsInfo)) display: none; @endif">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>feel free to filter results with the tabs below</h5>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('reps') }}" class="btn btn-danger float-end">Reset Search</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs tab">
                        <li class="nav-item">
                            <a href="#/" class="nav-link tablinks" onclick="openLevel(event, 'federalLevel')">Federal</a>
                        </li>
                        <li class="nav-item">
                            <a href="#/" class="nav-link tablinks" onclick="openLevel(event, 'stateLevel')">State</a>
                        </li>
                        <li class="nav-item">
                            <a href="#/" class="nav-link tablinks" onclick="openLevel(event, 'localLevel')">City/Local</a>
                        </li>
                    </ul>
                    <br>
                    @if(isset($repsInfo))
                    @foreach($repsInfo as $level => $repInfo)
                    <div id="{{ $level }}Level" class="tabcontent">
                    @foreach($repInfo as $rep)
                    <h2 class="text-muted">{{ $rep['name'] }}</h2>
                    <table class="table representativesTable">
                        <thead class="table-primary">
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Party</th>
                                <th scope="col">Address</th>
                                <th scope="col">Phone</th>
                                <th scope="col">Website</th>
                                <th scope="col">Email</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($rep['official'] as $official)
                            <tr>
                                <td>
                                    {{ $official['name'] }}
                                    <br>
                                    @if(isset($official['channels']))
                                    @foreach($official['channels'] as $socialMedia)
                                    <a
                                        href="https://{{ strtolower($socialMedia['type']) }}.com/{{ $socialMedia['id'] }}"
                                        class="socialMediaLink {{ strtolower($socialMedia['type']) }}"
                                        target="_blank"
                                    >
                                        <i class="fa-brands fa-{{ strtolower($socialMedia['type']) }} fa-xl"></i>
                                    </a>
                                    @endforeach
                                    @endif
                                </td>
                                <td>{{ $official['party'] }}</td>
                                <td>
                                @if(isset($official['geocodingSummaries']))
                                    {{ $official['geocodingSummaries'][0]['queryString'] }}
                                @else
                                    N/A
                                @endif
                                </td>
                                <td>
                                @if(isset($official['phones']))
                                    {{ $official['phones'][0] }}
                                @else
                                    N/A
                                @endif
                                </td>
                                <td>
                                @if(isset($official['urls']))
                                    @foreach($official['urls'] as $url)
                                        <a href="{{ $url }}" target="_blank">{{ $url }}</a>
                                        <br><br>
                                    @endforeach
                                @else
                                    N/A
                                @endif
                                </td>
                                <td>
                                    @if(isset($official['emails']))
                                        @foreach($official['emails'] as $email)
                                            <a href="mailto:{{ $email }}">{{ $email }}</a>
                                        @endforeach
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @endforeach
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('page-scripts')
        <script type="text/javascript" src="{{ secure_asset('js/representatives.js') }}"></script>
    @endpush

@endsection