@extends('layouts.master')
@section('title')
    <title> Project Detail</title>
@endsection
@section('content')
    <div class="row px-3">

        <div class="page-title-box">
            <h4 class="page-title">Project Detail</h4>

        </div>

    </div>
    {{-- page header end --}}
    <div class="row">
        <div class="col-sm-12">
            <div class="card widget-flat">
                <div class="card-body">

                    <h2 class="fw-bold mt-0">
                        {{ $data->name }}
                    </h2>
                    <p>{{ $data->description }}</p>

                    <p>
                        <i class="uil-calender fs-4 text-primary"></i>
                        Created
                        {{ \Carbon\Carbon::parse($data->created_at)->format('M d, Y') }}
                    </p>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col-->



    </div>
    <!-- end row -->
    <!-- row -->
    <div class="row" id="task-create">
        <div class="col-sm-12">
            <div class="card widget-flat">
                <div class="card-body">
                    <form>
                        @csrf
                        <div class="row justify-content-center">
                            <div class="col-md-8 mb-3">
                                <h3 class="text-center fw-normal mt-0">
                                    Create New Task
                                </h3>
                            </div>
                            <input type="text" name="id" hidden>
                            <input type="text" name="project_id" value="{{ $data->id }}" hidden>
                            <div class="col-md-7 mb-3">
                                <input type="text" name="title" class="form-control " placeholder="Title">
                                <span id="err-title" class="text-danger"></span>
                            </div>
                            <div class="col-md-7 mb-3">
                                <textarea name="description" class="form-control" rows="3" placeholder="Description"></textarea>
                                <span id="err-desc" class="text-danger"></span>
                            </div>
                            <div class="col-md-7 mb-3">
                                <input type="date" name="due_date" class="form-control" placeholder="Due Date">
                                <span id="err-due-date" class="text-danger"></span>

                            </div>



                        </div>
                        <div class="row justify-content-center">
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
                            </div>
                        </div>
                    </form>

                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col-->
    </div>


    <div class="container">
        <div class="row justify-content-between align-items-center mb-3">
            <div class="col-md-3 mb-3">
                <h3>Task</h3>
            </div>
            <div class="col-md-3 mb-3">
                <input type="text" class="form-control" id="search-task" placeholder="Search by Title">
            </div>
            <div class="col-md-3 mb-3">
                <select class="form-select" id="filter-status">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <button class="btn btn-primary" id="btn-create-task">Create Task</button>
            </div>
        </div>
    </div>

    <!-- end container -->
    <!-- table-->

    <div class="container">
        <div class="row" id="task-table">
            <table class="table table-bordered table-centered mb-0">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody id="task-table-body">


                </tbody>
            </table>
        </div>
    </div>
    <div class="d-flex justify-content-end mt-3">
        <div class="pagination" id="pagination"></div>

    </div>
@endsection

@section('js')
    <script>
        $('#task-create').hide();
        $('#task-table').hide();
        $(document).ready(function() {
            datalist();
            $('#btn-create-task').click(function() {
                $('#task-create').toggle();
            });

            // Create project-create
            $('#task-create').on('submit', 'form', function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('task.store') }}",
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(res) {

                        if (res.status === 'success') {
                            // Reset the form
                            $('form')[0].reset();

                            datalist();

                            Toast.fire({
                                icon: "success",
                                title: res.message
                            });
                        } else if (res.status === 'error') {
                            $('#err-title').text(res.errors.title || '');
                            $('#err-desc').text(res.errors.description || '');
                            $('#err-due-date').text(res.errors.due_date || '');
                        }

                    },

                });
            });

            // Handle edit button click

            $(document).on('click', '#btn-edit', function(e) {
                e.preventDefault();

                const id = $(this).val();
                $.ajax({
                    url: "{{ url('task/edit') }}/" + id,
                    type: 'GET',
                    success: function(res) {
                        if (res.status === 'success') {
                            // Populate the form with project data
                            $('input[name="title"]').val(res.data.title);
                            $('textarea[name="description"]').val(res.data.description);
                            $('input[name="id"]').val(res.data.id);
                            $('input[name="due_date"]').val(res.data.due_date ? new Date(res
                                .data.due_date).toISOString().split('T')[0] : '');
                            $('#task-create').show();
                        } else {
                            Toast.fire({
                                icon: "error",
                                title: res.message
                            });
                        }
                    },

                });

            });

            // Handle status change
            $(document).on('change', 'select[data-task-id]', function() {
                const taskId = $(this).data('task-id');
                const status = $(this).val();

                $.ajax({
                    url: "{{ url('task/update-status') }}/" + taskId,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        status: status
                    },
                    success: function(res) {
                        if (res.status === 'success') {
                            Toast.fire({
                                icon: "success",
                                title: res.message
                            });
                            datalist();
                        } else {
                            Toast.fire({
                                icon: "error",
                                title: res.message
                            });
                        }
                    },
                });
            });

            // Handle search input

            $("#search-task").on('change keyup paste', function() {
                datalist($(this).val(), "");
            });

            // Handle filter-status
            $("#filter-status").on('change', function() {
                datalist("", $(this).val());

            });



            // Handle delete button click
            $(document).on('click', '#btn-delete', function(e) {
                e.preventDefault();
                const id = $(this).val();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ url('task/delete') }}/" + id,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'

                            },
                            success: function(res) {
                                if (res.status === 'success') {
                                    datalist();
                                    Toast.fire({
                                        icon: "success",
                                        title: res.message
                                    });
                                } else {
                                    Toast.fire({
                                        icon: "error",
                                        title: res.message
                                    });
                                }
                            },
                        });
                    }
                });
            });


        });

        function datalist(search = '', status = '') {
            const segments = window.location.pathname.split('/');
            const projectId = segments[segments.length - 1];
            $('#task-table').hide();
            $('#task-create').hide();

            $.ajax({
                url: "{{ route('task.list', ['id' => '__ID__']) }}".replace('__ID__', projectId) + "?search=" +
                    search + "&status=" + status,
                type: 'GET',

                success: function(res) {
                    if (res.status === 'success') {
                        $('#task-table').show();
                        renderTasks(res.data.data);
                        renderPagination(res.data.links);

                    }


                },
            });
        }


        renderTasks = (data) => {
            const taskTableBody = document.getElementById('task-table-body');
            taskTableBody.innerHTML = '';
            if (data.length === 0) {
                taskTableBody.innerHTML = `
            <tr>
                <td colspan="4" class="text-center">No tasks found</td>
            </tr>
            `;
                return;
            }
            data.forEach((task, idx) => {
                const bgColor = idx % 2 === 0 ? '#c2d9ff' : '#c2ffef';
                taskTableBody.innerHTML += `
            <tr style="background-color: ${bgColor};">
                <td>${shortenString(task.title)}</td>
                <td>${task.due_date ? new Date(task.due_date).toLocaleDateString() : 'N/A'}</td>
                <td>
                    <select class="form-select form-select-sm" data-task-id="${task.id}">
                        <option value="pending" ${task.status === 'pending' ? 'selected' : ''}>Pending</option>
                        <option value="completed" ${task.status === 'completed' ? 'selected' : ''}>Completed</option>
                    </select>
                </td>
                <td class="text-center">
                <button class="btn btn-primary btn-sm" id="btn-edit" value="${task.id}">Edit</button>
                <button class="btn btn-danger btn-sm" id="btn-delete" value="${task.id}">Delete</button>
                </td>
            </tr>
            `;
            });
        };
        const renderPagination = (links) => {
            const pagination = document.getElementById('pagination');
            pagination.innerHTML = '';
            links.forEach(link => {
                if (link.url !== null) {
                    pagination.innerHTML += `
                <li class="page-item${link.active ? ' active' : ''}">
                    <a class="page-link pagination-link" href="${link.url}">${link.label}</a>
                </li>
                `;
                } else {
                    pagination.innerHTML += `
                <li class="page-item disabled">
                    <span class="page-link">${link.label}</span>
                </li>
                `;
                }
            });
            // Wrap in ul for Bootstrap style
            pagination.innerHTML = `<ul class="pagination justify-content-center">${pagination.innerHTML}</ul>`;

            // Attach click event for AJAX pagination
            $('.pagination-link').off('click').on('click', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(res) {
                        // You may want to update your list here
                        renderPagination(res.data.links);
                        // Add code to update your project list if needed
                        renderTasks(res.data.data);
                    }
                });
            });
        };

        function shortenString(text, maxLength = 70) {
            if (text.length <= maxLength) return text;
            return text.substring(0, maxLength) + '...';
        }
    </script>
@endsection
