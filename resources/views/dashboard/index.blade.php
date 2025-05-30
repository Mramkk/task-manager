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
            <div class="card widget-flat bg-primary">
                <div class="card-body">

                    <h2 class="text-light fw-normal mt-0" title="Number of Customers">
                        Welcome
                    </h2>
                    <p class="text-light">Manage your projects and tasks from your personal dashboard.</p>

                    <button id="btn-create-project" type="button" class="btn btn-light waves-effect waves-light">Create
                        Project</button>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col-->



    </div>
    <!-- end row -->
    <!-- row -->
    <div class="row" id="project-create">
        <div class="col-sm-12">
            <div class="card widget-flat">
                <div class="card-body">
                    <form>
                        @csrf
                        <div class="row justify-content-center">
                            <div class="col-md-8 mb-3">
                                <h3 class="text-center fw-normal mt-0">
                                    Create New Project
                                </h3>
                            </div>
                            <input type="text" name="id" hidden>
                            <div class="col-md-7 mb-3">
                                <input type="text" name="name" class="form-control " placeholder="Project Name">
                                <span id="err-name" class="text-danger"></span>
                            </div>
                            <div class="col-md-7 mb-3">
                                <textarea name="description" class="form-control" rows="3" placeholder="Project Description"></textarea>
                                <span id="err-desc" class="text-danger"></span>
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


    <!-- end row -->
    <div class="row" id="project-list">

    </div>
    <!-- end row -->

    <div class="d-flex justify-content-end">
        <div class="pagination" id="pagination"></div>

    </div>
@endsection

@section('js')
    <script>
        $('#project-create').hide();
        $(document).ready(function() {
            datalist();
            $('#btn-create-project').click(function() {
                $('#project-create').toggle();
            });

            // Create project-create
            $('#project-create').on('submit', 'form', function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('project.store') }}",
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(res) {

                        if (res.status === 'success') {
                            // Reset the form
                            $('form')[0].reset();
                            $('#project-create').hide();
                            datalist();

                            Toast.fire({
                                icon: "success",
                                title: res.message
                            });
                        } else if (res.status === 'error') {
                            $('#err-name').text(res.errors.name || '');
                            $('#err-desc').text(res.errors.description || '');
                        }

                    },

                });
            });

            // Handle edit button click

            $(document).on('click', '#btn-edit', function(e) {
                e.preventDefault();

                const id = $(this).val();
                $.ajax({
                    url: "{{ url('project/edit') }}/" + id,
                    type: 'GET',
                    success: function(res) {
                        if (res.status === 'success') {
                            // Populate the form with project data
                            $('input[name="name"]').val(res.data.name);
                            $('textarea[name="description"]').val(res.data.description);
                            $('input[name="id"]').val(res.data.id);
                            $('#project-create').show();
                        } else {
                            Toast.fire({
                                icon: "error",
                                title: res.message
                            });
                        }
                    },

                });

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
                            url: "{{ url('project/delete') }}/" + id,
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

        function datalist() {
            $.ajax({
                url: "{{ route('project.list') }}",
                type: 'GET',
                success: function(res) {
                    if (res.status === 'success') {
                        renderPagination(res.data.links);
                        renderProjects(res.data.data);

                    }


                },
            });
        }

        const renderProjects = (projects) => {
            const container = document.getElementById('project-list');
            container.innerHTML = '';
            projects.forEach(project => {
                container.innerHTML += `
        <div class="col-md-4">
            <div class="card widget-flat">
                <div class="card-body">
                    <h3 class="fw-bold mt-0">
                        ${project.name}
                    </h3>
                    <p>${shortenString(project.description) || 'N/A'}</p>
                    <div class="d-flex">
                      <p class="fw-bold fs-4 me-2">
                        <i class="uil-clipboard-notes fs-3 text-primary"></i>
                       Task
                        <span class="badge bg-primary">10</span>
                        </p>
                        <p class="fw-bold fs-4">
                        <i class="uil-check-circle fs-3 text-success"></i>
                        Completed
                        <span class="badge bg-primary">10</span>
                        </p>

                    </div>

                    <p>
                        <i class="uil-calender fs-4 text-primary"></i>
                        Created
                        ${new Date(project.created_at).toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' })}
                    </p>

                    <div class="text-center mt-3">

                         <a href="#" class="btn btn-primary waves-effect waves-light">View Project</a>
                         <button id="btn-edit" type="button"  value="${project.id}" class="btn btn-secondary waves-effect waves-light"><i class="uil-pen"></i></button>
                        <button id="btn-delete" value="${project.id}"  class="btn btn-danger waves-effect waves-light"><i class="uil-trash-alt"></i></button>



                    </div>

                </div>
            </div>
        </div>
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
                        renderProjects(res.data.data);
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
