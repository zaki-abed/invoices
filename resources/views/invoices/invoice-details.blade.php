@extends('layouts.master')
@section('css')
<!--- Internal Select2 css-->
<link href="{{URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
<!---Internal Fileupload css-->
<link href="{{URL::asset('assets/plugins/fileuploads/css/fileupload.css')}}" rel="stylesheet" type="text/css"/>
<!---Internal Fancy uploader css-->
<link href="{{URL::asset('assets/plugins/fancyuploder/fancy_fileupload.css')}}" rel="stylesheet" />
<!--Internal Sumoselect css-->
<link rel="stylesheet" href="{{URL::asset('assets/plugins/sumoselect/sumoselect-rtl.css')}}">
<!--Internal  TelephoneInput css-->
<link rel="stylesheet" href="{{URL::asset('assets/plugins/telephoneinput/telephoneinput-rtl.css')}}">
    <style>
        table th {
            font-weight: bold !important;
        }
    </style>
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">قائمة الفواتير</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                    تفاصيل الفاتورة</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
    {{-- Model Delete Attch --}}
    <div class="modal fade" id="delete_file" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">حذف المرفق</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('delete-file') }}" method="post">

                    {{ csrf_field() }}
                    <div class="modal-body">
                        <p class="text-center">
                        <h6 style="color:red"> هل انت متاكد من عملية حذف المرفق ؟</h6>
                        </p>

                        <input type="hidden" name="id_file" id="id_file" value="">
                        <input type="hidden" name="file_name" id="file_name" value="">
                        <input type="hidden" name="invoice_number" id="invoice_number" value="">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">الغاء</button>
                        <button type="submit" class="btn btn-danger">تاكيد</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <!-- row -->
    <div class="row">
        {{-- Tabs --}}
        <div class="col-xl-12">
        {{-- Alert Message --}}
        @if (session()->has('delete_file'))
        <div class="alert alert-success" role="alert">
            <button aria-label="Close" class="close" data-dismiss="alert" type="button">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>{{ session('delete_file') }}</strong>
        </div>
        @endif
        @if (session()->has('add_attachment'))
        <div class="alert alert-success" role="alert">
            <button aria-label="Close" class="close" data-dismiss="alert" type="button">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>{{ session('add_attachment') }}</strong>
        </div>
        @endif
        {{-- Errors --}}
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger" role="alert">
                    <button aria-label="Close" class="close" data-dismiss="alert" type="button"><span aria-hidden="true">&times;</span></button>
                    <strong>{{$error}}</strong>
                </div>
            @endforeach
        </div>
        @endif
            <!-- div -->
            <div class="card mg-b-20" id="tabs-style2">
                <div class="card-body">
                    <div class="text-wrap">
                        <div class="example">
                            <div class="panel panel-primary tabs-style-2">
                                <div class=" tab-menu-heading">
                                    <div class="tabs-menu1">
                                        <!-- Tabs -->
                                        <ul class="nav panel-tabs main-nav-line">
                                            <li><a href="#tab4" class="nav-link active" data-toggle="tab">معلومات
                                                    الفاتورة</a></li>
                                            <li><a href="#tab5" class="nav-link" data-toggle="tab">حالات الدفع</a></li>
                                            <li><a href="#tab6" class="nav-link" data-toggle="tab">المرفقات</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="panel-body tabs-menu-body main-content-body-right border">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tab4">
                                            <div class="table-responsive">
                                                <table id="example1" style="text-align:center"
                                                    class="table key-buttons text-md-nowrap table-striped">
                                                    <tbody>
                                                        <tr>
                                                            <th>رقم الفاتورة</th>
                                                            <td>{{ $invoice->invoice_number }}</td>
                                                            <th>تاريخ الإصدار</th>
                                                            <td>{{ $invoice->invoice_date }}</td>
                                                            <th>تاريخ الإستحقاق</th>
                                                            <td>{{ $invoice->due_date }}</td>
                                                            <th>القسم</th>
                                                            <td>{{ $invoice->section->section_name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>المنتج</th>
                                                            <td>{{ $invoice->product }}</td>
                                                            <th>مبلغ التحصيل</th>
                                                            <td>{{ $invoice->amount_collection }}</td>
                                                            <th>مبلغ العمولة</th>
                                                            <td>{{ $invoice->amount_commission }}</td>
                                                            <th>الخصم</th>
                                                            <td>{{ $invoice->discount }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>نسبة الضريبة</th>
                                                            <td>{{ $invoice->rate_vat }}</td>
                                                            <th>قيمة الضريبة</th>
                                                            <td>{{ $invoice->value_vat }}</td>
                                                            <th>الإجمالي مع الضريبة</th>
                                                            <td>{{ $invoice->total }}</td>
                                                            <th>الحالة الحالية</th>
                                                            @if ($invoice->value_status == 1)
                                                                <td>
                                                                    <span
                                                                        class="badge badge-pill badge-success">{{ $invoice->status }}</span>
                                                                </td>
                                                            @elseif ($invoice->value_status == 2)
                                                                <td>
                                                                    <span
                                                                        class="badge badge-pill badge-warning" style="color: #fff">{{ $invoice->status }}</span>
                                                                </td>
                                                            @else
                                                                <td>
                                                                    <span
                                                                        class="badge badge-pill badge-danger"  style="color: #fff">{{ $invoice->status }}</span>
                                                                </td>
                                                            @endif
                                                        </tr>
                                                        <tr>
                                                            <th>ملاحظات</th>
                                                            <td>{{ $invoice->note }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="tab5">
                                            <div class="table-responsive">
                                                <table id="example1" style="text-align:center"
                                                    class="table key-buttons text-md-nowrap table-striped">
                                                    <thead>
                                                        <th>#</th>
                                                        <th>رقم الفاتورة</th>
                                                        <th>نوع المنتج</th>
                                                        <th>القسم</th>
                                                        <th>حالة الدفع</th>
                                                        <th>تاريخ الدفع</th>
                                                        <th>ملاحظات</th>
                                                        <th>تاريخ الإضافة</th>
                                                        <th>المستخدم</th>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $i = 1;
                                                        @endphp
                                                        @foreach ($invoicesDetails as $item)
                                                            <tr>
                                                                <td>{{ $i++ }}</td>
                                                                <td>{{ $item->invoice_number }}</td>
                                                                <td>{{ $item->product }}</td>
                                                                <td>{{ $item->section->section_name }}</td>
                                                                @if ($item->value_status == 1)
                                                                    <td>
                                                                        <span
                                                                            class="badge badge-pill badge-success">{{ $item->status }}</span>
                                                                    </td>
                                                                @elseif ($item->value_status == 2)
                                                                    <td>
                                                                        <span
                                                                            class="badge badge-pill badge-warning" style="color: #fff">{{ $item->status }}</span>
                                                                    </td>
                                                                @else
                                                                    <td>
                                                                        <span
                                                                            class="badge badge-pill badge-danger">{{ $item->status }}</span>
                                                                    </td>
                                                                @endif
                                                                <td>{{ $item->payment_date }}</td>
                                                                <td>{{ $item->note }}</td>
                                                                <td>{{ $item->created_at }}</td>
                                                                <td>{{ $item->user }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="tab6">
                                            <div class="row mb-5">
                                                <div class="col-lg-12 col-md-12">
                                                    @can('اضافة مرفق')
                                                    <p class="text-danger">* صيغة المرفق pdf, jpeg ,.jpg , png </p>
                                                    <h5 class="card-title">إضافة مرفق</h5>
                                                    <form action="{{route('create-attachment')}}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="col-sm-12 col-md-12 mb-3">
                                                            <input type="file" name="pic" class="dropify" accept=".pdf,.jpg, .png, image/jpeg, image/png" data-height="70" />
                                                        </div>
                                                        <input type="hidden" name="invoice_number" value="{{$invoice->invoice_number}}">
                                                        <input type="hidden" name="invoice_id" value="{{$invoice->id}}">
                                                        <button class="btn btn-primary mr-2">Save</button>
                                                    </form>
                                                    @endcan
                                                </div>
                                            </div>
                                            <div class="table-responsive">
                                                <table id="example1" style="text-align:center"
                                                    class="table key-buttons text-md-nowrap table-striped">
                                                    <thead>
                                                        <th>#</th>
                                                        <th>اسم الملف</th>
                                                        <th>المستخدم</th>
                                                        <th>تاريخ الإضافة</th>
                                                        <th>العمليات</th>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $i = 1;
                                                        @endphp
                                                         @if ($invoiceAttachments->count() != 0)
                                                         @foreach ($invoiceAttachments as $item)
                                                            <tr>
                                                                <td>{{ $i++ }}</td>
                                                                <td>{{ $item->file_name }}</td>
                                                                <td>{{ $item->created_by }}</td>
                                                                <td>{{ $item->created_at }}</td>
                                                                <td colspan="2">

                                                                    <a class="btn btn-outline-success btn-sm"
                                                                        href="{{ url('view-file') }}/{{ $item->invoice_number }}/{{ $item->file_name }}"
                                                                        role="button"><i class="fas fa-eye"></i>&nbsp;
                                                                        عرض</a>

                                                                    <a class="btn btn-outline-info btn-sm"
                                                                        href="{{ url('download-file') }}/{{ $item->invoice_number }}/{{ $item->file_name }}"
                                                                        role="button"><i class="fas fa-download"></i>&nbsp;
                                                                        تحميل</a>

                                                                    <button class="btn btn-outline-danger btn-sm"
                                                                        data-toggle="modal"
                                                                        data-file_name="{{ $item->file_name }}"
                                                                        data-invoice_number="{{ $item->invoice_number }}"
                                                                        data-id_file="{{ $item->id }}"
                                                                        data-target="#delete_file">حذف</button>


                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                         @else
                                                         <tr>
                                                            <td colspan="5">لا يوجد مرفقات لهذه الفاتورة</td>
                                                         </tr>
                                                         @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /div -->
        {{-- / end Tabs --}}
    </div>
    <!-- row closed -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection
@section('js')
<!--Internal  Datepicker js -->
<script src="{{URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js')}}"></script>
<!-- Internal Select2 js-->
<script src="{{URL::asset('assets/plugins/select2/js/select2.min.js')}}"></script>
<!--Internal Fileuploads js-->
<script src="{{URL::asset('assets/plugins/fileuploads/js/fileupload.js')}}"></script>
<script src="{{URL::asset('assets/plugins/fileuploads/js/file-upload.js')}}"></script>
<!--Internal Fancy uploader js-->
<script src="{{URL::asset('assets/plugins/fancyuploder/jquery.ui.widget.js')}}"></script>
<script src="{{URL::asset('assets/plugins/fancyuploder/jquery.fileupload.js')}}"></script>
<script src="{{URL::asset('assets/plugins/fancyuploder/jquery.iframe-transport.js')}}"></script>
<script src="{{URL::asset('assets/plugins/fancyuploder/jquery.fancy-fileupload.js')}}"></script>
<script src="{{URL::asset('assets/plugins/fancyuploder/fancy-uploader.js')}}"></script>
<!--Internal  Form-elements js-->
<script src="{{URL::asset('assets/js/advanced-form-elements.js')}}"></script>
<script src="{{URL::asset('assets/js/select2.js')}}"></script>
<!--Internal Sumoselect js-->
<script src="{{URL::asset('assets/plugins/sumoselect/jquery.sumoselect.js')}}"></script>
<!-- Internal TelephoneInput js-->
<script src="{{URL::asset('assets/plugins/telephoneinput/telephoneinput.js')}}"></script>
<script src="{{URL::asset('assets/plugins/telephoneinput/inttelephoneinput.js')}}"></script>
    <script>
        $('#delete_file').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var id_file = button.data('id_file')
            var file_name = button.data('file_name')
            var invoice_number = button.data('invoice_number')
            var modal = $(this)
            modal.find('.modal-body #id_file').val(id_file);
            modal.find('.modal-body #file_name').val(file_name);
            modal.find('.modal-body #invoice_number').val(invoice_number);
        })
    </script>
@endsection
