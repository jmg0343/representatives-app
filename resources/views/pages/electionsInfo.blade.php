@extends('layouts.default')

@section('content')
    <div class="row">
        <div class="col-sm-12 text-center">
            <h5>Below is information regarding the upcoming {{ $electionsInfo['election']['name'] }}.</h5>
            <h6>Information provided by the Google Civic Information API</h6>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <ul class="nav nav-tabs justify-content-center" id="electionInfo" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active"
                            id="candidate-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#candidate"
                            type="button"
                            role="tab"
                            aria-controls="candidate"
                            aria-selected="true"
                    >
                        Election
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link"
                            id="referendum-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#referendum"
                            type="button"
                            role="tab"
                            aria-controls="referendum"
                            aria-selected="false"
                    >
                        Referendums
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link"
                            id="pollingLocation-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#pollingLocation"
                            type="button"
                            role="tab"
                            aria-controls="pollingLocation"
                            aria-selected="false"
                    >
                        Polling Locations
                    </button>
                </li>
            </ul>
        </div>
        <div class="col-sm-12 my-3">
            <div class="tab-content" id="electionInfoTabContent">
                <div class="tab-pane fade show active" id="candidate" role="tabpanel" aria-labelledby="candidate-tab">
                    <div class="col col-md-9 mx-auto">
                    @foreach($electionsInfo['contests'] as $contest)
                        @if($contest['type'] != 'Referendum')
                            <div class="card text-center">
                                <div class="card-header">
                                    {{ $contest['type'] }}
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $contest['office'] }}</h5>
                                    <p class="card-text">Number Elected: {{ $contest['numberElected'] }}</p>
                                    <p class="card-text">{{ $contest['district']['name'] }}</p>
                                    <a href="#" class="btn btn-primary">Candidate Info</a>
                                </div>
                                <div class="card-footer text-muted">
                                    Election Day: {{ $electionsInfo['election']['electionDay'] }}
                                </div>
                            </div>
                        @endif
                        <br>
                    @endforeach
                    </div>
                </div>
                <div class="tab-pane fade" id="referendum" role="tabpanel" aria-labelledby="referendum-tab">
                    <div class="col col-md-9 mx-auto">
                    @foreach($electionsInfo['contests'] as $contest)
                        @if($contest['type'] == 'Referendum')
                            <div class="card text-center">
                                <div class="card-header">
                                    {{ $contest['type'] }}
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $contest['referendumTitle'] }}</h5>
                                    <h6>{{ $contest['referendumSubtitle'] }}</h6>
                                    <br>
                                    <p class="blockquote text-start">{{ $contest['referendumText'] }}</p>
                                    <br>
                                    <p class="card-text">{{ $contest['district']['name'] }}</p>
                                </div>
                                <div class="card-footer text-muted">
                                    Election Day: {{ $electionsInfo['election']['electionDay'] }}
                                </div>
                            </div>
                            <br>
                        @endif
                    @endforeach
                    </div>
                </div>
                <div class="tab-pane fade" id="pollingLocation" role="tabpanel" aria-labelledby="pollingLocation-tab">
                    <h5 class="text-center">Polling Locations</h5>
                    @foreach($electionsInfo['pollingLocations'] as $pollingLocation)
                        <div class="card text-center" style="width: 20rem; margin: auto;">
                            <img src="https://cdn.wccftech.com/wp-content/uploads/2017/03/Google-Maps.jpg" class="card-img-top" alt="Google Map Coming Soon!">
                            <div class="card-body">
                                <h5 class="card-title">{{ $pollingLocation['address']['locationName'] }}</h5>
                                <h6>{{ $pollingLocation['address']['line1'] }}</h6>
                                @if(isset($pollingLocation['address']['line2']))
                                    <h6>{{ $pollingLocation['address']['line2'] }}</h6>
                                @endif
                                <h6>{{ $pollingLocation['address']['city'] }},
                                    {{ $pollingLocation['address']['state'] }}
                                    {{ $pollingLocation['address']['zip'] }}
                                </h6>
                            </div>
                        </div>
                    @endforeach
                    <br>
                    <h5 class="text-center">Early Voting Locations</h5>
                    @foreach($electionsInfo['earlyVoteSites'] as $pollingLocation)
                        <div class="card text-center" style="width: 20rem; margin: auto;">
                            <img src="https://cdn.wccftech.com/wp-content/uploads/2017/03/Google-Maps.jpg" class="card-img-top" alt="Google Map Coming Soon!">
                            <div class="card-body">
                                <h5 class="card-title">{{ $pollingLocation['address']['locationName'] }}</h5>
                                <h6>{{ $pollingLocation['address']['line1'] }}</h6>
                                @if(isset($pollingLocation['address']['line2']))
                                    <h6>{{ $pollingLocation['address']['line2'] }}</h6>
                                @endif
                                <h6>{{ $pollingLocation['address']['city'] }},
                                    {{ $pollingLocation['address']['state'] }}
                                    {{ $pollingLocation['address']['zip'] }}
                                </h6>
                            </div>
                        </div>
                    @endforeach
                    <br>
                    <h5 class="text-center">Ballot Drop Off Locations</h5>
                    @foreach($electionsInfo['dropOffLocations'] as $pollingLocation)
                        <div class="card text-center" style="width: 20rem; margin: auto;">
                            <img src="https://cdn.wccftech.com/wp-content/uploads/2017/03/Google-Maps.jpg" class="card-img-top" alt="Google Map Coming Soon!">
                            <div class="card-body">
                                <h5 class="card-title">{{ $pollingLocation['address']['locationName'] }}</h5>
                                <h6>{{ $pollingLocation['address']['line1'] }}</h6>
                                @if(isset($pollingLocation['address']['line2']))
                                    <h6>{{ $pollingLocation['address']['line2'] }}</h6>
                                @endif
                                <h6>{{ $pollingLocation['address']['city'] }},
                                    {{ $pollingLocation['address']['state'] }}
                                    {{ $pollingLocation['address']['zip'] }}
                                </h6>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection