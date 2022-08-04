@extends('layouts.default')

@section('content')
    <div class="row">
        @if(empty($repsInfo))
            <h3 class="text-center">Enter your address to see your elected representatives.</h3>
        @else
            <h3 class="text-center">Here's some information about your representatives</h3>
        @endif
        <form
            id="addressForm"
            action="{{ secure_url(route('reps.find')) }}"
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
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-6">
                            <h5>feel free to filter results with the tabs below</h5>
                        </div>
                        <div class="col-sm-6">
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
                                <th scope="col" class="hideOnMobile">Address</th>
                                <th scope="col">Phone</th>
                                <th scope="col" class="hideOnMobile">Website</th>
                                <th scope="col" class="hideOnMobile">Email</th>
                                <th scope="col">More Info</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($rep['official'] as $official)
                            <tr>
                                <td>
                                    <p class="repName">{{ $official[0]['name'] }}
                                    @if($official[0]['party'] == 'Democratic Party')
                                        <span class="democrat text-primary">(D)</span>
                                    @elseif($official[0]['party'] == 'Republican Party')
                                        <span class="republican text-danger">(R)</span>
                                    @else
                                        <span class="independent text-success">(I)</span>
                                    @endif
                                    </p>
                                    @if(isset($official[0]['channels']))
                                    @foreach($official[0]['channels'] as $socialMedia)
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
                                <td class="hideOnMobile">
                                @if(isset($official[0]['geocodingSummaries']))
                                    {{ $official[0]['geocodingSummaries'][0]['queryString'] }}
                                @else
                                    N/A
                                @endif
                                </td>
                                <td>
                                @if(isset($official[0]['phones']))
                                    {{ $official[0]['phones'][0] }}
                                @else
                                    N/A
                                @endif
                                </td>
                                <td class="hideOnMobile">
                                @if(isset($official[0]['urls']))
                                    @foreach($official[0]['urls'] as $url)
                                        <a href="{{ $url }}" target="_blank">{{ $url }}</a>
                                        <br><br>
                                    @endforeach
                                @else
                                    N/A
                                @endif
                                </td>
                                <td class="hideOnMobile">
                                    @if(isset($official[0]['emails']))
                                        @foreach($official[0]['emails'] as $email)
                                            <a href="mailto:{{ $email }}">{{ $email }}</a>
                                        @endforeach
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#rep-{{ $official[1] }}">
                                        Learn More
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="rep-{{ $official[1] }}" tabindex="-1" aria-labelledby="rep-{{ $official[1] }}Label" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="rep-{{ $official[1] }}Label">
                                                        {{ $rep['name'] }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-lg-4 text-center">
                                                            <p>
                                                            @if(isset($official[0]['photoUrl']))
                                                                <img
                                                                    class="img-responsive"
                                                                    src="{{ $official[0]['photoUrl'] }}"
                                                                    alt="{{ $official[0]['name'] }}_photo"
                                                                    style="max-width: 200px;"
                                                                >
                                                            @else
                                                            <img
                                                                src="{{ secure_asset('images/no_image_placeholder.png') }}"
                                                                alt="{{ $official[0]['name'] }}_photo"
                                                                style="max-width: 200px;"
                                                            >
                                                            @endif
                                                            </p>
                                                            @if(isset($official[0]['channels']))
                                                                @foreach($official[0]['channels'] as $socialMedia)
                                                                    <a
                                                                        href="https://{{ strtolower($socialMedia['type']) }}.com/{{ $socialMedia['id'] }}"
                                                                        class="socialMediaLink {{ strtolower($socialMedia['type']) }}"
                                                                        target="_blank"
                                                                    >
                                                                        <i class="fa-brands fa-{{ strtolower($socialMedia['type']) }} fa-xl"></i>
                                                                    </a>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                        <div class="col-lg-8">
                                                            <h3 style="margin-top: 10px;">{{ $official[0]['name'] }}</h3>
                                                            @if($official[0]['party'] == 'Democratic Party')
                                                                <span class="democrat text-muted">Democrat</span>
                                                            @elseif($official[0]['party'] == 'Republican Party')
                                                                <span class="republican text-muted">Republican</span>
                                                            @else
                                                                <span class="independent text-muted">Independent</span>
                                                            @endif
                                                            <hr>
                                                            <p class="repPhone">
                                                                <strong>Phone: </strong>
                                                                @if(isset($official[0]['phones']))
                                                                    {{ $official[0]['phones'][0] }}
                                                                @else
                                                                    N/A
                                                                @endif
                                                            </p>
                                                            <p class="repAddress">
                                                                <strong>Address: </strong>
                                                                @if(isset($official[0]['geocodingSummaries']))
                                                                    {{ $official[0]['geocodingSummaries'][0]['queryString'] }}
                                                                @else
                                                                    N/A
                                                                @endif
                                                            </p>
                                                            @if(isset($official[0]['emails']))
                                                                <p class="repEmail">
                                                                    <strong>Email: </strong>
                                                                    @foreach($official[0]['emails'] as $email)
                                                                        <a href="mailto:{{ $email }}">{{ $email }}</a>
                                                                    @endforeach
                                                                </p>
                                                            @endif
                                                            <strong>Websites: </strong>
                                                            @if(isset($official[0]['urls']))
                                                                <ul class="list-group list-group-flush">
                                                                @foreach($official[0]['urls'] as $url)
                                                                <li class="list-group-item"><a href="{{ $url }}" target="_blank">{{ $url }}</a></li>
                                                                @endforeach
                                                                </ul>
                                                            @else
                                                            N/A
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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

@endsection