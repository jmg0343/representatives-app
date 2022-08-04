@extends('layouts.default')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="text-center">
                <h2>Upcoming Elections</h2>
                <p><b>***</b>
                    Data is reliant on Google Civic API, which is constantly updating.
                    I am currently working on solutions to fill any gaps in data.
                    <b>***</b></p>
                <p>Click to expand and find polling locations</p>
            </div>
            <div class="accordion" id="electionAccordion">
                @foreach($elections as $election)
                <div class="accordion-item">
                    <h2 class="accordion-header" id="electionHeading-{{ $loop->index }}">
                        <button class="accordion-button collapsed"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#electionCollapse-{{ $loop->index }}"
                                aria-expanded="false"
                                aria-controls="electionCollapse-{{ $loop->index }}"
                        >
                            {{ $election['name'] }} <br>
                            {{ $election['electionDay'] }}
                        </button>
                    </h2>
                    <div id="electionCollapse-{{ $loop->index }}"
                         class="accordion-collapse collapse"
                         aria-labelledby="electionHeading-{{ $loop->index }}"
                         data-bs-parent="#electionAccordion">
                        <div class="accordion-body">
                            <h5 class="text-center">Find polling locations and ballot information</h5>
                            <form
                                id="addressForm"
                                action="{{ secure_url(route('elections.info', $election['id'])) }}"
                                method="POST"
                                style="@if(isset($repsInfo)) display: none; @endif"
                            >
                                @csrf
                                <div class="mb-3">
                                    <label for="address" class="form-label">Street Address</label>
                                    <input type="text"
                                           class="form-control"
                                           id="address"
                                           name="address"
                                           aria-describedby="address"
                                           required
                                    >
                                </div>
                                <div class="row">
                                    <div class="mb-3 col-sm-6">
                                        <label for="city" class="form-label">City</label>
                                        <input type="text"
                                               class="form-control"
                                               id="city"
                                               name="city"
                                               aria-describedby="city"
                                               required
                                        >
                                    </div>
                                    <div class="mb-3 col-sm-6">
                                        <label class="form-label" for="state">State</label>
                                        <select class="form-select form-control"
                                                name="state" id="state"
                                                aria-label="State"
                                                required
                                        >
                                            <option selected>
                                                {{ strtoupper(substr($election['ocdDivisionId'], -2)) }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection