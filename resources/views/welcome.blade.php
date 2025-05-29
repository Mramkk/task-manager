@extends('layouts.master')
@section('title')
    <title> Dashboard</title>
@endsection
@section('content')
    <div class="row px-3">

        <div class="page-title-box">
            <h4 class="page-title">Dashboard</h4>

        </div>

    </div>
    {{-- page header end --}}
    <div class="row">
        <div class="col-sm-12">
            <div class="card widget-flat">
                <div class="card-body">
                    <div class="float-end">
                        <i class="mdi mdi-pulse widget-icon"></i>
                    </div>
                    <h5 class="text-muted fw-normal mt-0" title="Number of Customers">
                        Orders
                    </h5>
                    <h3 class="mt-3 mb-3">0</h3>

                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col-->
        <div class="col-sm-12">
            <div class="card widget-flat">
                <div class="card-body">
                    <div class="float-end">
                        <i class="mdi mdi-pulse widget-icon"></i>
                    </div>
                    <h5 class="text-muted fw-normal mt-0" title="Number of Customers">
                        Notes
                    </h5>
                    <h3 class="mt-3 mb-3">1</h3>

                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col-->


    </div>
    <!-- end row -->
@endsection

