<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('images/logo.png') }}" rel="icon" type="image/png" sizes="16x16" />
    <title>@yield('title')</title>

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />

    <!-- Google Fonts -->
    <!-- <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" /> -->

    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/5.0.0/mdb.min.css" rel="stylesheet" />

    {{-- datatable --}}
    <link href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css" rel="stylesheet" />

    {{-- Daterange Picker --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    {{-- select2 select multiple --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    {{-- pin code --}}
    <link rel="stylesheet" href="{{ asset('css/pin-code.css') }}">

    {{-- image viwer --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.10.5/viewer.min.css">

    <!-- {{-- custom css --}} -->
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />

    @yield('extra_css')

    {{-- Laragear/WebAuthn --}}
    {{-- @vite('resources/js/app.js') --}}
    @vite('resources/js/vendor/webauthn/webauthn.js')
</head>

<body>

    <div class="page-wrapper chiller-theme">

        <div class="app_header">
            <div class="col-md-10 col-12">
                <div class="d-flex justify-content-between align-items-center">
                    @if (request()->is('home'))
                        <a href="javascript:void(0);" id="show-sidebar">
                            <i class="fas fa-bars"></i>
                        </a>
                    @else
                        <a href="javascript:void(0);" class="text-black py-1 px-2" onclick="window.history.go(-1);">
                            <i class="fas fa-angle-left"></i>
                        </a>
                    @endif

                    <h5 class="text-capitalize mb-0" style="font-size: 18px;">@yield('title')</h5>
                    <a href="javascript:void(0);">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" width="35px">
                    </a>
                    {{-- @if (request()->is('home'))
                    @else
                        <a href="javascript:void(0);"></a>
                    @endif --}}
                </div>
            </div>
        </div>

        @if (request()->is('home'))
            <nav id="sidebar" class="sidebar-wrapper">
                <div class="sidebar-content">
                    <div class="sidebar-brand">
                        <a href="javascript:void(0);" class="text-white">Priate HR</a>
                        <div id="close-sidebar">
                            <i class="fas fa-times"></i>
                        </div>
                    </div>
                    <div class="sidebar-header">
                        <div class="user-pic">
                            <img class="img-responsive img-rounded" src="{{ Auth::user()->img_path() }}">
                        </div>
                        <div class="user-info">
                            <span class="user-name">
                                <strong>{{ Auth::user()->name }}</strong>
                            </span>
                            <span class="user-role">{{ Auth::user()->roles[0]->name }}</span>
                            <span class="user-status">
                                <i class="fa fa-circle"></i>
                                <span>Online</span>
                            </span>
                        </div>
                    </div>
                    <!-- sidebar-header  -->
                    <div class="sidebar-menu">
                        <ul>
                            <li class="header-menu">
                                <span>Menu</span>
                            </li>

                            <li>
                                <a href="{{ route('home') }}">
                                    <i class="fas fa-home"></i>
                                    <span>Home</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('profile.edit', Auth::user()->id) }}">
                                    <i class="fas fa-user-edit"></i>
                                    <span>Edit Profile</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('change_password') }}">
                                    <i class="fas fa-user-lock"></i>
                                    <span>Change Password</span>
                                </a>
                            </li>

                            @can('view company setting')
                                <li>
                                    <a href="{{ route('company_setting.show', 1) }}">
                                        <i class="fas fa-building"></i>
                                        <span>Company Setting</span>
                                    </a>
                                </li>
                            @endcan

                            @can('view employee')
                                <li>
                                    <a href="{{ route('employee.index') }}">
                                        <i class="fas fa-users"></i>
                                        <span>Employees</span>
                                    </a>
                                </li>
                            @endcan

                            @can('view department')
                                <li>
                                    <a href="{{ route('department.index') }}">
                                        <i class="fas fa-sitemap"></i>
                                        <span>Departments</span>
                                    </a>
                                </li>
                            @endcan

                            @can('view role')
                                <li>
                                    <a href="{{ route('role.index') }}">
                                        <i class="fas fa-user-shield"></i>
                                        <span>Roles</span>
                                    </a>
                                </li>
                            @endcan

                            @can('view permission')
                                <li>
                                    <a href="{{ route('permission.index') }}">
                                        <i class="fas fa-shield-alt"></i>
                                        <span>Permission</span>
                                    </a>
                                </li>
                            @endcan

                            @can('view project')
                                <li>
                                    <a href="{{ route('project.index') }}">
                                        <i class="fas fa-tools"></i>
                                        <span>Projects</span>
                                    </a>
                                </li>
                            @endcan

                            @can('view attendance')
                                <li>
                                    <a href="{{ route('attendance.index') }}">
                                        <i class="fas fa-tasks"></i>
                                        <span>Attendance (Employees)</span>
                                    </a>
                                </li>
                            @endcan

                            @can('view attendance')
                                <li>
                                    <a href="{{ route('attendance_overview') }}">
                                        <i class="fas fa-calendar-check"></i>
                                        <span>Attendance (Overview)</span>
                                    </a>
                                </li>
                            @endcan

                            @can('view salary')
                                <li>
                                    <a href="{{ route('salary.index') }}">
                                        <i class="fas fa-dollar-sign"></i>
                                        <span>Salaries</span>
                                    </a>
                                </li>
                            @endcan

                            @can('view payroll')
                                <li>
                                    <a href="{{ route('payroll') }}">
                                        <i class="fas fa-money-check"></i>
                                        <span>Payroll</span>
                                    </a>
                                </li>
                            @endcan

                            <li>
                                <a href="#" class="logout_btn">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>Logout</span>
                                </a>
                            </li>

                        </ul>
                    </div>
                    <!-- sidebar-menu  -->
                </div>
                <!-- sidebar-content  -->
            </nav>
        @endif

        <div class="py-4 px-1 content">
            <div class="d-flex justify-content-center">
                <div class="col-md-10 col-12">
                    @yield('content')
                </div>
            </div>
        </div>

        <div class="app_footer">
            <div class="col-md-10 col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('home') }}">
                        <i class="fas fa-home"></i>
                        <p class="mb-0">Home</p>
                    </a>
                    <a href="{{ route('attendance_scan') }}">
                        <i class="fas fa-user-check"></i>
                        <p class="mb-0">Attendance</p>
                    </a>
                    <a href="{{ route('my_project.index') }}">
                        <i class="fas fa-folder-open"></i>
                        <p class="mb-0">Projects</p>
                    </a>
                    <a href="{{ route('profile.index') }}">
                        <i class="fas fa-user"></i>
                        <p class="mb-0">Profile</p>
                    </a>
                </div>
            </div>
        </div>

    </div>

    <!-- jquery -->
    <script src="{{ asset('jquery/jquery-3.6.1.min.js') }}"></script>

    <!-- MDB -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/5.0.0/mdb.min.js"></script>

    {{-- datatable --}}
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/g/mark.js(jquery.mark.min.js)"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.10.13/features/mark.js/datatables.mark.js"></script>

    {{-- Daterange Picker --}}
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <!-- Laravel Javascript Validation -->
    <script type="text/javascript" src="{{ url('vendor/jsvalidation/js/jsvalidation.js') }}"></script>

    {{-- Sweet alert 2 --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- Sweet alert 1 --}}
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    {{-- select2 select multiple --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    {{-- pin code --}}
    <script src="{{ asset('js/pin-code.js') }}"></script>

    {{-- image viwer --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.10.5/viewer.min.js"></script>

    <script>
        jQuery(function($) {


            let token = document.head.querySelector('meta[name="csrf-token"]');
            if (token) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': token.content
                    }
                });
            } else {
                console.log('CSRF token not found!.');
            }

            $(".sidebar-dropdown > a").click(function() {
                $(".sidebar-submenu").slideUp(200);
                if (
                    $(this).parent().hasClass("active")
                ) {
                    $(".sidebar-dropdown").removeClass("active");
                    $(this).parent().removeClass("active");
                } else {
                    $(".sidebar-dropdown").removeClass("active");
                    $(this).next(".sidebar-submenu").slideDown(200);
                    $(this).parent().addClass("active");
                }
            });

            $("#close-sidebar").click(function(e) {
                e.preventDefault();
                $(".page-wrapper").removeClass("toggled");
            });

            $("#show-sidebar").click(function(e) {
                e.preventDefault();
                $(".page-wrapper").addClass("toggled");
            });

            /*  */
            if ($('.select-multiple')) {
                $('.select-multiple').css({
                    width: '100%'
                })
            }

            if ($('.table-bordered')) {
                $('.table-bordered').addClass('table-hover');
            }

            @if (request()->is('home'))
                document.addEventListener('click', function(e) {
                    if (document.getElementById('show-sidebar').contains(e.target)) {
                        $(".page-wrapper").addClass("toggled")
                    } else if (!document.getElementById('sidebar').contains(e.target)) {
                        $(".page-wrapper").removeClass("toggled")
                    }
                })
            @endif

            /* datatable default value */
            $.extend(true, $.fn.dataTable.defaults, {
                processing: true,
                serverSide: true,
                responsive: true,
                mark: true,
                columnDefs: [{
                        target: 0,
                        class: "control",
                        orderable: false,
                    },
                    {
                        target: 'no-sort',
                        orderable: false,
                    },
                    {
                        targets: 'no-search',
                        searchable: false,
                    },
                    {
                        target: 'hidden',
                        visible: false,
                    },
                ],
                language: {
                    processing: '<img src="/images/loading.gif" width="60px"/> <p class="m-0">...Loading...</p>',
                    paginate: {
                        previous: '<i class="far fa-arrow-alt-circle-left"></i>',
                        next: '<i class="far fa-arrow-alt-circle-right"></i>'
                    }
                }
            });

            /* select2 */
            $('.select-multiple').select2();

            $(document).on('click', '.logout_btn', function(e) {
                e.preventDefault();

                swal({
                        text: "Do you want to logout?",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                url: '/logout',
                                method: "POST",
                                success: function(response) {
                                    window.location.reload();
                                }
                            })
                        }
                    });
            })

            @if (session('create'))
                Swal.fire({
                    icon: 'success',
                    title: 'Successfully Created',
                    text: '{{ session('create') }}',
                    confirmButtonText: 'Continue'
                })
            @endif

            @if (session('update'))
                Swal.fire({
                    icon: 'success',
                    title: 'Successfully Updated',
                    text: '{{ session('update') }}',
                    confirmButtonText: 'Continue'
                })
            @endif


        });
    </script>

    @yield('script')
</body>

</html>
