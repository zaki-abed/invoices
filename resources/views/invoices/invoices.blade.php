@extends('layouts.master')
@section('title')
    قائمة الفواتير
@endsection
@section('css')
    <!-- Internal Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <!--Internal   Notify -->
    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    <style>
        .fa.fa-solid.fa-plus {
            font-size: 13px;
            margin-right: 0px;
            margin-left: 8px;
        }
    </style>
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الفواتير</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ قائمة
                    الفواتير</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    {{-- Modal Delete Invoice --}}
    <div class="modal fade" id="delete_invoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">حذف الفاتورة</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('invoices.destroy') }}" method="post">
                    {{-- @method('delete') --}}
                    @csrf
                    <div class="modal-body">
                        <label for="">هل أنت متأكد من عملية الحذف؟</label>
                        <input type="hidden" name="invoice_id" id="invoice_id" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-danger">تأكيد</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- Modal Archive Invoice --}}
    <div class="modal fade" id="invoice_archive" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">أرشفة الفاتورة</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('destroy-invoices') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <label for="">هل أنت متأكد من أرشفة الفاتورة؟</label>
                        <input type="hidden" name="invoice_id" id="invoice_id" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-info">تأكيد</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- Alert --}}
    {{-- Delete Invoice --}}
    @if (session()->has('invoice_delete'))
        <script>
            window.onload = function() {
                notif({
                    msg: "تم حذف الفاتورة بنجاح",
                    type: "success"
                })
            }
        </script>
    @endif
    @if (session()->has('update_status'))
        <script>
            window.onload = function() {
                notif({
                    msg: "تم تعديل حالة الدفع بنجاح",
                    type: "success"
                })
            }
        </script>
    @endif
    @if(session()->has('invoiec_archived'))
        <script>
            window.onload = function() {
                notif({
                    msg: "تم أرشفة الفاتورة نجاح",
                    type: "success"
                })
            }
        </script>
    @endif
    <!-- row -->
    <div class="row">
        <!--div-->
        <div class="col-xl-12">
            <div class="card mg-b-20">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between">
                        <div class="col-sm-6 col-md-3 mg-t-10 mg-sm-t-0">
                            @can('اضافة فاتورة')
                            <a href="{{ route('invoices.create') }}"><button
                                class="btn btn-primary btn-with-icon btn-block"><i class="fa fa-solid fa-plus"></i>
                                إضافة فواتير</button>
                            </a>
                            @endcan
                        </div>
                        <div>
                            @can('تصدير EXCEL')
                            <a href="{{ route('invoices-export') }}"><button
                                class="btn btn-primary btn-with-icon btn-block"><i class="fa fa-solid fa-file-excel ml-1" style="font-size: 14px;"></i>
                                تصدير إكسل</button>
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example1" class="table key-buttons text-md-nowrap">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0">#</th>
                                    <th class="border-bottom-0">رقم الفاتورة</th>
                                    <th class="border-bottom-0">تاريخ الفاتورة</th>
                                    <th class="border-bottom-0">تاريخ الإستحقاق</th>
                                    <th class="border-bottom-0">المنتج</th>
                                    <th class="border-bottom-0">القسم</th>
                                    <th class="border-bottom-0">الخصم</th>
                                    <th class="border-bottom-0">نسبة الضريبة</th>
                                    <th class="border-bottom-0">قيمة الضريبة</th>
                                    <th class="border-bottom-0">الإجمالي</th>
                                    <th class="border-bottom-0">الحالة</th>
                                    <th class="border-bottom-0">ملاحظات</th>
                                    <th class="border-bottom-0">العمليات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach ($invoices as $item)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $item->invoice_number }}</td>
                                        <td>{{ $item->invoice_date }}</td>
                                        <td>{{ $item->due_date }}</td>
                                        <td>{{ $item->product }}</td>
                                        <td>{{ $item->section->section_name }}</td>
                                        <td>{{ $item->discount }}</td>
                                        <td>{{ $item->rate_vat }}</td>
                                        <td>{{ $item->value_vat }}</td>
                                        <td>{{ $item->total }}</td>
                                        <td>
                                            @if ($item->value_status == 1)
                                                <span class="badge badge-pill badge-success">{{ $item->status }}</span>
                                            @elseif($item->value_status == 2)
                                                <span class="badge badge-pill badge-warning"
                                                    style="color: #fff">{{ $item->status }}</span>
                                            @else
                                                <span class="badge badge-pill badge-danger"
                                                    style="color: #fff">{{ $item->status }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $item->note }}</td>
                                        <td>
                                            <div class="row row-xs">
                                                <div class="col-sm-6 col-md-3">
                                                    <div class="dropdown dropright">
                                                        <button aria-expanded="false" aria-haspopup="true"
                                                            class="btn ripple btn-info" data-toggle="dropdown"
                                                            id="droprightMenuButton" type="button"><i
                                                                class="fas fa-caret-right ml-2 d-inline"></i>العمليات</button>
                                                        <div aria-labelledby="droprightMenuButton"
                                                            class="dropdown-menu tx-13">

                                                            <a class="dropdown-item text-success"
                                                                href="{{ route('invoice-details', $item->id) }}"><i
                                                                    class="fa fa-solid fa-eye ml-1"></i> عرض</a>
                                                            @can('تعديل الفاتورة')
                                                            <a class="dropdown-item text-primary"
                                                                href="{{ route('edit-invoice', $item->id) }}"><i
                                                                    class="fas fa-edit ml-1"></i> تعديل</a>
                                                            @endcan
                                                            @can('حذف الفاتورة')
                                                            <a class="dropdown-item text-danger"
                                                                data-invoice_id="{{ $item->id }}" href="#"
                                                                data-target="#delete_invoice" data-toggle="modal"><i
                                                                    class="fas fa-trash-alt ml-1"></i> حذف</a>
                                                            @endcan
                                                            @can('تغير حالة الدفع')
                                                            <a class="dropdown-item text-warning"
                                                                href="{{ URL::route('edit_status', [$item->id]) }}"><i
                                                                    class="fa fa-solid fa-money-bill-wave ml-1"></i> تعديل
                                                                حالة الدفع</a>
                                                            @endcan
                                                            @can('ارشفة الفاتورة')
                                                            @if($item->value_status == 1)
                                                            <a class="dropdown-item text-info"
                                                                data-invoice_id="{{ $item->id }}" href="#"
                                                                data-target="#invoice_archive" data-toggle="modal">
                                                                <i class="fa fa-archive ml-1" aria-hidden="true"></i> أرشفة الفاتورة</a>
                                                            @endif
                                                            @endcan
                                                            @can('طباعةالفاتورة')
                                                            <a class="dropdown-item text-success"
                                                                href="{{ route('print-invoice', $item->id) }}">
                                                                <i class="fa fa-print ml-1" aria-hidden="true"></i> طباعة الفاتورة</a>
                                                            @endcan
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--/div-->
    </div>
    <!-- row closed -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection
@section('js')
    <!-- Internal Data tables -->
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
    <!--Internal  Datatable js -->
    <script src="{{ URL::asset('assets/js/table-data.js') }}"></script>
    <!--Internal  Notify js -->
    <script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/notify/js/notifit-custom.js') }}"></script>

    <script>
        $('#delete_invoice').on('show.bs.modal', function(event) {
            var a = $(event.relatedTarget)
            var invoice_id = a.data('invoice_id')
            var modal = $(this)
            modal.find('.modal-body #invoice_id').val(invoice_id);
        })
        $('#invoice_archive').on('show.bs.modal', function(event) {
            var a = $(event.relatedTarget)
            var invoice_id = a.data('invoice_id')
            var modal = $(this)
            modal.find('.modal-body #invoice_id').val(invoice_id);
        })
    </script>
@endsection
