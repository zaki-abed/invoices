@extends('invoices.layout_invoices')
@section('title')
    قائمة الفواتير المؤرشفة
@endsection
@section('content1')
    @php
    $i = 1;
    @endphp
    @foreach ($invoicesTrashed as $item)
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
                    <span class="badge badge-pill badge-warning" style="color: #fff">{{ $item->status }}</span>
                @else
                    <span class="badge badge-pill badge-danger" style="color: #fff">{{ $item->status }}</span>
                @endif
            </td>
            <td>{{ $item->note }}</td>
            <td>
                <div class="row row-xs">
                    <div class="col-sm-6 col-md-3">
                        <div class="dropdown dropright">
                            <button aria-expanded="false" aria-haspopup="true" class="btn ripple btn-info"
                                data-toggle="dropdown" id="droprightMenuButton" type="button"><i
                                    class="fas fa-caret-right ml-2 d-inline"></i>العمليات</button>
                            <div aria-labelledby="droprightMenuButton" class="dropdown-menu tx-13">
                                <a class="dropdown-item text-success" href="{{ route('invoice-details', $item->id) }}"><i
                                        class="fa fa-solid fa-eye ml-1"></i> عرض</a>
                                <a class="dropdown-item text-primary" href="{{ route('edit-invoice', $item->id) }}"><i
                                        class="fas fa-edit ml-1"></i> تعديل</a>
                                <a class="dropdown-item text-danger" data-invoice_id="{{ $item->id }}" href="#"
                                    data-target="#delete_invoice" data-toggle="modal"><i class="fas fa-trash-alt ml-1"></i>
                                    حذف</a>
                                <a class="dropdown-item text-warning"
                                    href="{{ URL::route('edit_status', [$item->id]) }}"><i
                                        class="fa fa-solid fa-money-bill-wave ml-1"></i> تعديل
                                    حالة الدفع</a>
                                <a class="dropdown-item text-secondary" data-invoice_id="{{ $item->id }}" href="#"
                                    data-target="#invoice_unarchive" data-toggle="modal"><i class="fa fa-undo ml-1" aria-hidden="true"></i>
                                    التراجع عن الأرشفة</a>
                            </div>
                        </div>
                    </div>
                </div>

            </td>
        </tr>
    @endforeach
@endsection
