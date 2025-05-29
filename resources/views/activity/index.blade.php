@extends('layouts.master')
@section('title')
    <title> Activities</title>
@endsection
@section('content')
    <div class="row px-3">

        <div class="page-title-box">
            <h4 class="page-title">Activities</h4>

        </div>

    </div>
    {{-- page header end --}}
    <div class="container">
        <div class="row">
        @foreach ($data as $item )
            <div class="col-sm-12">
                <div class="card widget-flat">
                    <div class="card-body">

                    <b>{{$item->activity}}</b>
                        <p class="text-muted mt-2 mb-0">
                            <i class="mdi mdi-clock-outline"></i>
                            {{ $item->created_at->diffForHumans() }}
                        </p>


                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col-->
        @endforeach


    <div class="mt-4">
        {{ $data->links('vendor.pagination.bootstrap-5') }}
    </div>


    </div>
    <!-- end row -->
    </div>
@endsection

