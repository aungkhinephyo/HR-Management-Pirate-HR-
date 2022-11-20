<div class="row">
    <h5 class="fw-bolder">Tasks</h5>

    <div class="col-md-4 mb-md-0 mb-3">
        <div class="card">
            <div class="card-header bg-warning text-white fw-bold"><i class="fas fa-tasks"></i> Pending</div>
            <div class="card-body bg-lightwarning">

                <div id="pending_taskboard">
                    @foreach (collect($project->tasks)->where('status', 'pending')->sortBy('serial_number') as $task)
                        <div class="task_item" data-id="{{ $task->id }}">
                            <div class="d-flex justify-content-between mb-1">
                                <h6>{{ $task->name }}</h6>
                                <div class="task_item_actions">
                                    <a href="" class="text-primary edit_task_btn"
                                        data-task="{{ base64_encode(json_encode($task)) }}"
                                        data-task-members="{{ base64_encode(json_encode(collect($task->members)->pluck('id')->toArray())) }}"><i
                                            class="far fa-edit"></i></a>
                                    <a href="" class="text-danger delete_task_btn"
                                        data-id="{{ $task->id }}"><i class="fas fa-trash-alt"></i></a>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-end">
                                <div>
                                    <p class="mb-0">
                                        <span class="me-2"><i class="fas fa-clock"></i>
                                            {{ Carbon\Carbon::parse($task->start_date)->format('M d') }}</span>
                                        <span><i class="fas fa-stopwatch"></i>
                                            {{ Carbon\Carbon::parse($task->deadline)->format('M d') }}</span>
                                    </p>
                                    @if ($task->priority === 'high')
                                        <span class="badge rounded-pill bg-danger">High</span>
                                    @elseif($task->priority === 'middle')
                                        <span class="badge rounded-pill bg-info">Middle</span>
                                    @elseif($task->priority === 'low')
                                        <span class="badge rounded-pill bg-dark">Low</span>
                                    @endif

                                </div>
                                <div>
                                    @foreach ($task->members as $member)
                                        <img src="{{ $member->img_path() }}" class="profile_thumbnail3" />
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-grid">
                    <a href="" id="add_pending_task_btn" class="btn btn-lg btn-dark mt-3"><i
                            class="fas fa-plus-circle me-2"></i> Add
                        Task</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-md-0 mb-3">
        <div class="card">
            <div class="card-header bg-info text-white fw-bold"><i class="fas fa-tasks"></i> In Progress</div>
            <div class="card-body bg-lightinfo">

                <div id="in_progress_taskboard">
                    @foreach (collect($project->tasks)->where('status', 'in_progress')->sortBy('serial_number') as $task)
                        <div class="task_item" data-id="{{ $task->id }}">
                            <div class="d-flex justify-content-between mb-1">
                                <h6>{{ $task->name }}</h6>
                                <div class="task_item_actions">
                                    <a href="" class="text-primary edit_task_btn"
                                        data-task="{{ base64_encode(json_encode($task)) }}"
                                        data-task-members="{{ base64_encode(json_encode(collect($task->members)->pluck('id')->toArray())) }}"><i
                                            class="far fa-edit"></i></a>
                                    <a href="" class="text-danger delete_task_btn"
                                        data-id="{{ $task->id }}"><i class="fas fa-trash-alt"></i></a>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-end">
                                <div>
                                    <p class="mb-0">
                                        <span class="me-2"><i class="fas fa-clock"></i>
                                            {{ Carbon\Carbon::parse($task->start_date)->format('M d') }}</span>
                                        <span><i class="fas fa-stopwatch"></i>
                                            {{ Carbon\Carbon::parse($task->deadline)->format('M d') }}</span>
                                    </p>
                                    @if ($task->priority === 'high')
                                        <span class="badge rounded-pill bg-danger">High</span>
                                    @elseif($task->priority === 'middle')
                                        <span class="badge rounded-pill bg-info">Middle</span>
                                    @elseif($task->priority === 'low')
                                        <span class="badge rounded-pill bg-dark">Low</span>
                                    @endif

                                </div>
                                <div>
                                    @foreach ($task->members as $member)
                                        <img src="{{ $member->img_path() }}" class="profile_thumbnail3" />
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-grid">
                    <a href="" id="add_in_progress_task_btn" class="btn btn-lg btn-dark mt-3"><i
                            class="fas fa-plus-circle me-2"></i> Add
                        Task</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-md-0 mb-3">
        <div class="card">
            <div class="card-header bg-success text-white fw-bold"><i class="fas fa-tasks"></i> Complete</div>
            <div class="card-body bg-lightsuccess">

                <div id="complete_taskboard">
                    @foreach (collect($project->tasks)->where('status', 'complete')->sortBy('serial_number') as $task)
                        <div class="task_item" data-id="{{ $task->id }}">
                            <div class="d-flex justify-content-between mb-1">
                                <h6>{{ $task->name }}</h6>
                                <div class="task_item_actions">
                                    <a href="" class="text-primary edit_task_btn"
                                        data-task="{{ base64_encode(json_encode($task)) }}"
                                        data-task-members="{{ base64_encode(json_encode(collect($task->members)->pluck('id')->toArray())) }}"><i
                                            class="far fa-edit"></i></a>
                                    <a href="" class="text-danger delete_task_btn"
                                        data-id="{{ $task->id }}"><i class="fas fa-trash-alt"></i></a>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-end">
                                <div>
                                    <p class="mb-0">
                                        <span class="me-2"><i class="fas fa-clock"></i>
                                            {{ Carbon\Carbon::parse($task->start_date)->format('M d') }}</span>
                                        <span><i class="fas fa-stopwatch"></i>
                                            {{ Carbon\Carbon::parse($task->deadline)->format('M d') }}</span>
                                    </p>
                                    @if ($task->priority === 'high')
                                        <span class="badge rounded-pill bg-danger">High</span>
                                    @elseif($task->priority === 'middle')
                                        <span class="badge rounded-pill bg-info">Middle</span>
                                    @elseif($task->priority === 'low')
                                        <span class="badge rounded-pill bg-dark">Low</span>
                                    @endif

                                </div>
                                <div>
                                    @foreach ($task->members as $member)
                                        <img src="{{ $member->img_path() }}" class="profile_thumbnail3" />
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-grid">
                    <a href="" id="add_complete_task_btn" class="btn btn-lg btn-dark mt-3"><i
                            class="fas fa-plus-circle me-2"></i> Add
                        Task</a>
                </div>
            </div>
        </div>
    </div>
</div>
