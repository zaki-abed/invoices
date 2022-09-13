@extends('layouts.master')
@section('title')
طباعة الفاتورة
@endsection
@section('css')
<style>
    @media print{
        body, #btn-print{
            visibility: hidden !important;
        }
        #divPrint{
            visibility: visible !important;
        }
    }
</style>
@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">الفواتير</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/  معاينة طباعة الفاتورة</span>
						</div>
					</div>
				</div>
				<!-- breadcrumb -->
@endsection
@section('content')
				<!-- row -->
				<div class="row row-sm">
					<div class="col-md-12 col-xl-12">
						<div class=" main-content-body-invoice" id="divPrint">
							<div class="card card-invoice">
								<div class="card-body">
                                    <div class="invoice-header">
										<h1 class="invoice-title">فاتورة تحصيل</h1>
										<div class="billed-from">
											<h6>شركة أكسس لاين</h6>
											<p>فلسطين، غزة، شارع الشهداء، عمارة حسنية، الطابق الخامس<br>
											059-5502091<br>zaki.abed@gamilcom</p>
										</div><!-- billed-from -->
									</div>
									<div class="row mg-t-20">
										<div class="col-md">
											<label class="tx-gray-600">فاتورة للسيد:</label>
											<div class="billed-to">
												<h6>بهجت صابر</h6>
                                                <p>مصر، القاهرة، شارع ريس، عمارة الملكية، الطابق الأول<br>
                                                059-592359<br>bahjet@gamilcom</p>
											</div>
										</div>
										<div class="col-md">
											<label class="tx-gray-600">معلومات الفاتورة</label>
											<p class="invoice-info-row"><span>رقم الفاتورة</span> <span>{{$invoice->invoice_number}}</span></p>
											<p class="invoice-info-row"><span>تاريخ الإصدار</span> <span>{{$invoice->invoice_date}}</span></p>
											<p class="invoice-info-row"><span>تاريخ الإستحقاق</span> <span>{{$invoice->due_date}}</span></p>
											<p class="invoice-info-row"><span>القسم</span> <span>{{$invoice->section->section_name}}</span></p>
										</div>
									</div>
									<div class="table-responsive mg-t-40">
										<table class="table table-invoice border text-md-nowrap mb-0">
											<thead>
												<tr>
													<th class="wd-20p">#</th>
													<th class="wd-40p">المنتج</th>
													<th class="tx-center">مبلغ التحصيل</th>
													<th class="tx-right">مبلغ العمولة</th>
													<th class="tx-right">الإجمالي</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>1</td>
													<td class="tx-12">{{$invoice->product}}</td>
													<td class="tx-center">{{ number_format($invoice->amount_collection, 2) }}</td>
													<td class="tx-right">{{ number_format($invoice->amount_commission, 2) }}</td>
													<td class="tx-right">{{ number_format(($invoice->amount_commission + $invoice->amount_collection), 2) }}</td>
												</tr>
												<tr>
													<td class="valign-middle" colspan="2" rowspan="4">
														<div class="invoice-notes">
															<label class="main-content-label tx-13">Notes</label>
															<p style="color: rgb(0 0 0 / 65%)">{{$invoice->note}}</p>
														</div><!-- invoice-notes -->
													</td>
													<td class="tx-right">الإجمالي</td>
													<td class="tx-right" colspan="2">{{number_format(($invoice->amount_commission + $invoice->amount_collection), 2)}}</td>
												</tr>
												<tr>
													<td class="tx-right">نسبة الضريبة ({{$invoice->rate_vat}})</td>
													<td class="tx-right" colspan="2">{{$invoice->value_vat}}</td>
												</tr>
												<tr>
													<td class="tx-right">الخصم</td>
													<td class="tx-right" colspan="2">-{{$invoice->discount}}</td>
												</tr>
												<tr>
													<td class="tx-right tx-uppercase tx-bold tx-inverse">الإجمالي شامل الضريبة</td>
													<td class="tx-right" colspan="2">
														<h4 class="tx-primary tx-bold">{{ number_format($invoice->total, 2) }}</h4>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
									<hr class="mg-b-40">
									<a href="#" class="btn btn-danger float-left mt-3 mr-2" id="btn-print" onclick="window.print();">
										<i class="mdi mdi-printer ml-1"></i>Print
									</a>
								</div>
							</div>
						</div>
					</div><!-- COL-END -->
				</div>
				<!-- row closed -->
			</div>
			<!-- Container closed -->
		</div>
		<!-- main-content closed -->
@endsection
@section('js')
<!--Internal  Chart.bundle js -->
<script src="{{URL::asset('assets/plugins/chart.js/Chart.bundle.min.js')}}"></script>
@endsection
