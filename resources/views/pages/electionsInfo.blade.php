@extends('layouts.default')

@section('content')
    <div class="row">
        <div class="col-sm-12 text-center">
            <h2>{{ $electionsInfo['election']['name'] }}</h2>
            <p><b>***</b>
                Data is reliant on Google Civic API, which is constantly updating.
                I am currently working on solutions to fill any gaps in data.
                <b>***</b></p>
            <div class="my-3">
                <h5>Need voter registration information?</h5>
                <button class="btn-sm btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">Click Here</button>

                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
                    <div class="offcanvas-header">
                        <h5 id="offcanvasRightLabel">{{ $electionsInfo['state'][0]['name'] }} Election Administration</h5>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="list-group list-group-flush">
                        @foreach($electionsInfo['state'][0]['electionAdministrationBody'] as $key => $electionUrl)
                            <li class="list-group-item text-start">
                                @if(
                                    $key == 'electionInfoUrl' ||
                                    $key == 'electionRegistrationUrl' ||
                                    $key == 'electionRegistrationConfirmationUrl' ||
                                    $key == 'absenteeVotingInfoUrl' ||
                                    $key == 'votingLocationFinderUrl'
                                    )
                                <a href="{{ url($electionUrl) }}" target="_blank">
                                    {{ ucfirst(preg_replace('/(?<!\ )[A-Z]/', ' $0', $key)) }}
                                </a>
                                @endif
                            </li>
                        @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <br>

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
                    @if(isset($electionsInfo['contests']))
                    @foreach($electionsInfo['contests'] as $contest)
                        @if($contest['type'] != 'Referendum')
                            <div class="card text-center">
                                <div class="card-header">
                                    @if(isset($contest['primaryParty']))
                                        {{ ucfirst(strtolower($contest['primaryParty'])) }}
                                    @endif
                                     {{ $contest['type'] }}
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $contest['office'] }}</h5>
                                    <p class="card-text">{{ $contest['district']['name'] }}</p>
                                    <p class="card-text">Number Elected: {{ $contest['numberElected'] }}</p>

                                    {{-- START MODAL --}}
                                <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#contest-{{ $loop->index }}">
                                        Candidate Information
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="contest-{{ $loop->index }}" tabindex="-1" aria-labelledby="contest-{{ $loop->index }}Label" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="contest-{{ $loop->index }}Label">Candidates</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-start">
                                                    @if(isset($contest['candidates']))
                                                        <ul class="list-group list-group-flush">
                                                        @foreach($contest['candidates'] as $candidate)
                                                            <li class="list-group-item">
                                                                {{ $candidate['name'] }}<br>
                                                                <span class="text-muted">
                                                                    {{ ucfirst(strtolower($candidate['party'])) }}
                                                                </span>
                                                            </li>
                                                        @endforeach
                                                        </ul>
                                                    @else
                                                        <p>Unopposed Candidate or Incumbant</p>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- END MODAL --}}

                                </div>
                                <div class="card-footer text-muted">
                                    Election Day: {{ $electionsInfo['election']['electionDay'] }}
                                </div>
                            </div>
                        <br>
                        @endif
                    @endforeach
                    @endif
                    </div>
                </div>
                <div class="tab-pane fade" id="referendum" role="tabpanel" aria-labelledby="referendum-tab">
                    <div class="col col-md-9 mx-auto">
                    @if(isset($electionsInfo['contests']))
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
                    @endif
                    </div>
                </div>
                <div class="tab-pane fade" id="pollingLocation" role="tabpanel" aria-labelledby="pollingLocation-tab">
                    <h5 class="text-center">Polling Locations</h5>
                    @if(isset($electionsInfo['pollingLocations']))
                    <div class="row">
                        @foreach($electionsInfo['pollingLocations'] as $pollingLocation)
                            <div class="col">
                                <div class="card" style="width: 20rem; margin: auto;">
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
                            </div>
                        @endforeach
                    </div>
                    @endif
                    <br>
                    <h5 class="text-center">Early Voting Locations</h5>
                    @if(isset($electionsInfo['earlyVoteSites']))
                    @foreach($electionsInfo['earlyVoteSites'] as $pollingLocation)
                        <div class="card" style="width: 20rem; margin: auto;">
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
                    @endif
                    <br>
                    <h5 class="text-center">Ballot Drop Off Locations</h5>
                    @if(isset($electionsInfo['dropOffLocations']))
                    @foreach($electionsInfo['dropOffLocations'] as $pollingLocation)
                        <div class="card" style="width: 20rem; margin: auto;">
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
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection