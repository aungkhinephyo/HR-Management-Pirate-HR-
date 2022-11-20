@extends('layouts.master')
@section('title', 'Project Details')
@section('extra_css')
    <style>
        .input-group-text {
            min-width: 97px !important;
            font-size: 14px !important;
            background: #f1f1f1;
        }

        .input-group>.form-control {
            font-size: 14px !important;
        }

        .label-text {
            font-size: 14px !important;
        }

        .select2-container {
            z-index: 1100 !important;
        }

        /* sortable js */
        .sortable-ghost {
            background: #eee !important;
            border: 2px dashed #000 !important;
        }
    </style>
@endsection
@section('content')

    <div class="row">

        <div class="@if ($project->images || $project->files) col-md-9 @else col-md-12 @endif">

            <div class="card mb-3">
                <div class="card-body">

                    <h5 class="fw-bolder text-center text-uppercase mb-4">{{ $project->name }}</h5>
                    <p class="fw-bold mb-1">Start Date : <span class="text-muted">{{ $project->start_date }}</span></p>
                    <p class="fw-bold mb-1">Deadline : <span class="text-muted">{{ $project->deadline }}</span></p>
                    <p class="fw-bold mb-1">Priority :
                        @if ($project->priority === 'high')
                            <span class="badge rounded-pill bg-danger">{{ ucfirst($project->priority) }}</span>
                        @elseif($project->priority === 'middle')
                            <span class="badge rounded-pill bg-info">{{ ucfirst($project->priority) }}</span>
                        @elseif($project->priority === 'low')
                            <span class="badge rounded-pill bg-dark">{{ ucfirst($project->priority) }}</span>
                        @endif
                    </p>
                    <p class="fw-bold mb-3">Status :
                        @if ($project->status === 'pending')
                            <span class="badge rounded-pill bg-warning">{{ ucfirst($project->status) }}</span>
                        @elseif($project->status === 'in_progress')
                            <span class="badge rounded-pill bg-info">{{ ucfirst($project->status) }}</span>
                        @elseif($project->status === 'complete')
                            <span class="badge rounded-pill bg-success">{{ ucfirst($project->status) }}</span>
                        @endif
                    </p>

                    <div class="mb-3">
                        <p class="fw-bold mb-2">Description :</p>
                        <span class="text-muted">{{ $project->description }}</span>
                    </div>

                    <div class="d-flex justify-content-between">
                        <div class="">
                            <h6 class="fw-bold mb-2">Leaders</h6>
                            @foreach ($project->leaders ?? [] as $leader)
                                <img src="{{ $leader->img_path() }}" class="profile_thumbnail2" data-mdb-toggle="tooltip"
                                    title="{{ $leader->name }}" />
                            @endforeach
                        </div>

                        <div class="">
                            <h6 class="fw-bold mb-2">Members</h6>
                            @foreach ($project->members ?? [] as $member)
                                <img src="{{ $member->img_path() }}" class="profile_thumbnail2" data-mdb-toggle="tooltip"
                                    title="{{ $member->name }}" />
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>

        </div>

        @if ($project->images || $project->files)
            <div class="col-md-3">
                @if ($project->images)
                    <div class="card mb-3 ">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3">Project Images</h6>
                            <div id="images" class="preview_img">
                                @foreach ($project->images as $image)
                                    <img src="{{ $project->img_path() . '/' . $image }}" />
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                @if ($project->files)
                    <div class="card mb-3">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3">Project PDF Files</h6>
                            <div class="d-flex flex-wrap">
                                @foreach ($project->files as $file)
                                    <a href="{{ $project->file_path() . '/' . $file }}" target="_blank"
                                        class="pdf_thumbnail" data-mdb-toggle="tooltip" title="{{ $file }}">
                                        <i class="fas fa-file-pdf fa-2x"></i>
                                        <span> File {{ $loop->iteration }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <div class="col-md-12">
            <div class="card">
                <div id="task_data" class="card-body"></div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        $(document).ready(function() {

            @if ($project->images)
                new Viewer(document.getElementById('images'));
            @endif

            var project_id = {{ $project->id }};
            var leaders = @json($project->leaders);
            var members = @json($project->members);


            function activeSortable() {
                var pending_taskboard = document.getElementById('pending_taskboard');
                var in_progress_taskboard = document.getElementById('in_progress_taskboard');
                var complete_taskboard = document.getElementById('complete_taskboard');

                var sortable = Sortable.create(pending_taskboard, {
                    group: "taskboard",
                    draggable: ".task_item",
                    ghostClass: "sortable-ghost",
                    animation: 200,
                    store: {
                        set: function(sortable) {
                            var order = sortable.toArray();
                            localStorage.setItem('pending_taskboard', order.join(','));
                        }
                    },
                    onSort: function(evt) {
                        setTimeout(function() {
                            var pending_taskboard = localStorage.getItem(
                                'pending_taskboard');

                            $.ajax({
                                url: `/task_draggable?project_id=${project_id}&pending_taskboard=${pending_taskboard}`,
                                type: 'GET',
                                success: function(response) {}
                            })
                        }, 1000);
                    },
                });

                var sortable = Sortable.create(in_progress_taskboard, {
                    group: "taskboard",
                    draggable: ".task_item",
                    forceFallback: false,
                    ghostClass: "sortable-ghost",
                    animation: 200,
                    store: {
                        set: function(sortable) {
                            var order = sortable.toArray();
                            localStorage.setItem('in_progress_taskboard', order.join(','));
                        }
                    },
                    onSort: function(evt) {
                        setTimeout(function() {
                            var in_progress_taskboard = localStorage.getItem(
                                'in_progress_taskboard');

                            $.ajax({
                                url: `/task_draggable?project_id=${project_id}&in_progress_taskboard=${in_progress_taskboard}`,
                                type: 'GET',
                                success: function(response) {}
                            })
                        }, 1000);
                    },
                });

                var sortable = Sortable.create(complete_taskboard, {
                    group: "taskboard",
                    draggable: ".task_item",
                    forceFallback: false,
                    ghostClass: "sortable-ghost",
                    animation: 200,
                    store: {
                        set: function(sortable) {
                            var order = sortable.toArray();
                            localStorage.setItem('complete_taskboard', order.join(','));
                        },
                    },
                    onSort: function(evt) {
                        setTimeout(function() {
                            var complete_taskboard = localStorage.getItem(
                                'complete_taskboard');

                            $.ajax({
                                url: `/task_draggable?project_id=${project_id}&complete_taskboard=${complete_taskboard}`,
                                type: 'GET',
                                success: function(response) {}
                            })
                        }, 1000);
                    },
                });
            }

            taskData();

            function taskData() {
                $.ajax({
                    url: `/task_data?project_id=${project_id}`,
                    type: 'GET',
                    success: function(response) {
                        $('#task_data').html(response);
                        activeSortable();
                    },
                })

            }

            $(document).on('click', '#add_pending_task_btn', function(e) {
                e.preventDefault();
                addTask('Pending', 'pending');
            })
            $(document).on('click', '#add_in_progress_task_btn', function(e) {
                e.preventDefault();
                addTask('In Progress', 'in_progress');
            })
            $(document).on('click', '#add_complete_task_btn', function(e) {
                e.preventDefault();
                addTask('Complete', 'complete');
            })

            $(document).on('click', '.edit_task_btn', function(e) {
                e.preventDefault();
                var task = JSON.parse(atob($(this).data('task')));
                var task_members = JSON.parse(atob($(this).data('task-members')));

                // console.log(task_members);

                var task_member_options = '';
                leaders.forEach(function(leader) {
                    task_member_options +=
                        `<option value="${leader.id}" ${(task_members.includes(leader.id) ? 'selected' : '')}>${leader.name}</option>`;
                })
                members.forEach(function(member) {
                    task_member_options +=
                        `<option value="${member.id}" ${(task_members.includes(member.id) ? 'selected' : '')}>${member.name}</option>`;
                })

                Swal.fire({
                    title: `Edit Task`,
                    html: `
                    <form id="edit_task_form">
                        <input type="hidden" name="project_id" value="${project_id}"/>
                        <div class="input-group mb-3">
                            <span class="input-group-text">Task Name</span>
                            <input type="text" class="form-control form-control-sm" name="name" value="${task.name}"/>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text">Description</span>
                            <textarea class="form-control form-control-sm" name="description">${task.description}</textarea>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text">Start Date</span>
                            <input type="text" class="form-control form-control-sm datepicker" name="start_date" value="${task.start_date}"/>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text">Deadline</span>
                            <input type="text" class="form-control form-control-sm datepicker" name="deadline" value="${task.deadline}"/>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text">Priority</span>
                            <select class="form-select form-select-sm" name="priority">
                                <option value="">—— Please Choose ——</option>
                                <option value="high" ${(task.priority === 'high' ? 'selected' : '')}>High</option>
                                <option value="middle" ${(task.priority === 'middle' ? 'selected' : '')}>Middle</option>
                                <option value="low" ${(task.priority === 'low' ? 'selected' : '')}>Low</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <span class="label-text">Task Member</span>
                            <select name="members[]" class="form-select select-multiple" multiple>
                                ${task_member_options}
                            </select>
                        </div>
                    </form>
                    `,
                    showCancelButton: false,
                    confirmButtonText: 'Confirm',
                }).then((result) => {
                    var form_data = $("#edit_task_form").serialize();
                    // console.log(form_data);
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/task/${task.id}`,
                            type: 'PUT',
                            data: form_data,
                            success: function(response) {
                                taskData();
                            }
                        })
                    }
                })

                $('.datepicker').daterangepicker({
                    "singleDatePicker": true,
                    "showDropdowns": true,
                    "autoApply": true,
                    "locale": {
                        "format": "YYYY-MM-DD"
                    },
                });
                $('.select-multiple').select2();


            })

            $(document).on('click', '.delete_task_btn', function(e) {
                e.preventDefault();

                var id = $(this).data('id');

                swal({
                        text: "Are you sure to delete this task?",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {

                            $.ajax({
                                    url: `/task/${id}`,
                                    method: "DELETE",
                                })
                                .done(function(response) {
                                    taskData();
                                });

                            swal("This task has been deleted!", {
                                icon: "success",
                            });
                        }
                    });
            })

            function addTask(name, status) {
                var task_member_options = '';
                leaders.forEach(function(leader) {
                    task_member_options += `<option value="${leader.id}">${leader.name}</option>`;
                })
                members.forEach(function(member) {
                    task_member_options += `<option value="${member.id}">${member.name}</option>`;
                })

                Swal.fire({
                    title: `Add ${name} Task`,
                    html: `
                    <form id="task_form">
                        <input type="hidden" name="project_id" value="${project_id}"/>
                        <input type="hidden" name="status" value="${status}"/>
                        <div class="input-group mb-3">
                            <span class="input-group-text">Task Name</span>
                            <input type="text" class="form-control form-control-sm" name="name" />
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text">Description</span>
                            <textarea class="form-control form-control-sm" name="description"></textarea>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text">Start Date</span>
                            <input type="text" class="form-control form-control-sm datepicker" name="start_date"/>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text">Deadline</span>
                            <input type="text" class="form-control form-control-sm datepicker" name="deadline"/>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text">Priority</span>
                            <select class="form-select form-select-sm" name="priority">
                                <option value="">—— Please Choose ——</option>
                                <option value="high">High</option>
                                <option value="middle">Middle</option>
                                <option value="low">Low</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <span class="label-text">Task Member</span>
                            <select name="members[]" class="form-select select-multiple" multiple>
                                ${task_member_options}
                            </select>
                        </div>
                    </form>
                    `,
                    showCancelButton: false,
                    confirmButtonText: 'Confirm',
                }).then((result) => {
                    var form_data = $("#task_form").serialize();
                    // console.log(form_data);
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/task',
                            type: 'POST',
                            data: form_data,
                            success: function(response) {
                                taskData();
                            }
                        })
                    }
                })

                $('.datepicker').daterangepicker({
                    "singleDatePicker": true,
                    "showDropdowns": true,
                    "autoApply": true,
                    "locale": {
                        "format": "YYYY-MM-DD"
                    },
                });
                $('.select-multiple').select2();
            }

        })
    </script>
@endsection
