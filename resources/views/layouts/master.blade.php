<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ trans('global.site_title') }}</title>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
        <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
        
        @yield('styles')
        <script>
            window.trans = <?php
// copy all translations from /resources/lang/CURRENT_LOCALE/* to global JS variable
$lang_files = File::files(resource_path() . '/lang/' . App::getLocale());
$trans = [];
foreach ($lang_files as $f) {
    $filename = pathinfo($f)['filename'];
    $trans[$filename] = trans($filename);
}
echo json_encode($trans);
?>;
            window.Laravel = <?php
echo json_encode([
    'csrfToken' => csrf_token(),
    'userId' => Auth::user()->id,
    'permissions' => Auth::user()->permissions()->pluck('title')->toArray()
]);
?>;
        </script>
    </head>
    <body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
        <!-- Site wrapper -->
        <div id="app" class="wrapper">
            <!-- Navbar -->
            <nav class="main-header navbar navbar-expand navbar-white navbar-light">
                <!-- Left navbar links -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                    </li>
                    <li class="nav-item d-none d-sm-inline-block">
                        <a href="#" class="nav-link">Contact</a>
                    </li>
                </ul>

                <!-- SEARCH FORM -->
                <form class="form-inline ml-3">
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-navbar" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Right navbar links -->
                @include('partials.navbar')
            </nav>
            <!-- /.navbar -->

            <!-- Main Sidebar Container -->
            @include('partials.menu')

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6 pl-0">
                                <h1> @yield('title')</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    @yield('breadcrumb')
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <!-- Main content -->
                <section class="content">
                    @yield('content')
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->

            <footer class="main-footer">
                <div class="float-right d-none d-sm-block">
                    <b>Version</b> 3.0.0-rc.5
                </div>
                <strong>Copyright &copy; 2014-2019 <a href="http://adminlte.io">AdminLTE.io</a>.</strong> All rights
                reserved.
            </footer>
            <!-- Logout Form -->
            <form id="logoutform" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>

            <!-- Control Sidebar -->
            <aside class="control-sidebar control-sidebar-dark">
                <!-- Control sidebar content goes here -->
            </aside>
            <!-- /.control-sidebar -->
        </div>
        <!-- ./wrapper -->

        <script src="{{ asset('js/app.js') }}"></script>
        <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/select/1.3.0/js/dataTables.select.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.flash.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.colVis.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
        <script src="{{ asset('js/main.js') }}"></script>
        <script>
               $(function() {
               let copyButtonTrans = '{{ trans('global.datatables.copy') }}'
                       let csvButtonTrans = '{{ trans('global.datatables.csv') }}'
                       let excelButtonTrans = '{{ trans('global.datatables.excel') }}'
                       let pdfButtonTrans = '{{ trans('global.datatables.pdf') }}'
                       let printButtonTrans = '{{ trans('global.datatables.print') }}'
                       let colvisButtonTrans = '{{ trans('global.datatables.colvis') }}'

                       let languages = {
                       'en': 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/English.json'
                       };
               $.extend(true, $.fn.dataTable.Buttons.defaults.dom.button, { className: 'btn' })
                       $.extend(true, $.fn.dataTable.defaults, {
                       language: {
                       url: languages.{{ app() -> getLocale() }}
                       },
                               columnDefs: [{
                               orderable: false,
                                       className: 'select-checkbox',
                                       targets: 0
                               }, {
                               orderable: false,
                                       searchable: false,
                                       targets: - 1
                               }],
                               select: {
                               style:    'multi+shift',
                                       selector: 'td:first-child'
                               },
                               order: [],
                               scrollX: true,
                               pageLength: 100,
                               dom: 'lBfrtip<"actions">',
                               buttons: [
                               {
                               extend: 'copy',
                                       className: 'btn-default',
                                       text: copyButtonTrans,
                                       exportOptions: {
                                       columns: ':visible'
                                       }
                               },
                               {
                               extend: 'csv',
                                       className: 'btn-default',
                                       text: csvButtonTrans,
                                       exportOptions: {
                                       columns: ':visible'
                                       }
                               },
                               {
                               extend: 'excel',
                                       className: 'btn-default',
                                       text: excelButtonTrans,
                                       exportOptions: {
                                       columns: ':visible'
                                       }
                               },
                               {
                               extend: 'pdf',
                                       className: 'btn-default',
                                       text: pdfButtonTrans,
                                       exportOptions: {
                                       columns: ':visible'
                                       }
                               },
                               {
                               extend: 'print',
                                       className: 'btn-default',
                                       text: printButtonTrans,
                                       exportOptions: {
                                       columns: ':visible'
                                       }
                               },
                               {
                               extend: 'colvis',
                                       className: 'btn-default',
                                       text: colvisButtonTrans,
                                       exportOptions: {
                                       columns: ':visible'
                                       }
                               }
                               ]
                       });
               $.fn.dataTable.ext.classes.sPageButton = '';
               });
               function setCurrentOrganization()
               {

               $.ajax({
               headers: {'x-csrf-token': _token},
                       method: 'POST',
                       url: "{{ route('users.setCurrentOrganization') }}",
                       data: {
                       current_organization_id: $('#current_organization_id').val(),
                               _method: 'PATCH',
                       }
               })
                       .done(function () { location.reload() })
               }
        </script>
        @yield('scripts')

    </body>
</html>
