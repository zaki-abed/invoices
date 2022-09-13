<?php

namespace App\Http\Controllers;

use App\Invioce;
use App\Section;

use Illuminate\Http\Request;

class ReportInvoiceController extends Controller
{
    // Invoices

    public function index(){
        return view('reports.reports_invoices');
    }

    public function search(Request $request){
        $radio = $request->radio;
        if($radio == 1){
            if ($request->type && $request->start_at == '' && $request->end_at == ''){
               $invoices = Invioce::select('*')->where('status', '=', $request->type)->get();
               $type = $request->type;
               return view('reports.reports_invoices', compact('type'))->withDetails($invoices);
            }else{
              $start_at = date($request->start_at);
              $end_at = date($request->end_at);
              $type = $request->type;
              $invoices = Invioce::whereBetween('invoice_date', [$start_at, $end_at])->where('status', '=', $request->type)->get();
              return view('reports.reports_invoices', compact('type', 'start_at', 'end_at'))->withDetails($invoices);
            }
        }else{
            $invoices = Invioce::select('*')->where('invoice_number', '=', $request->invoice_number)->get();
            return view('reports.reports_invoices')->withDetails($invoices);
        }
    }

    // Customer
    public function indexCustomer(){

        $sections = Section::all();
        return view('reports.report_customer', compact('sections'));
    }

    public function searchCustomer(Request $request){
        if($request->section && $request->product && $request->start_at =='' && $request->end_at=='') {
            $invoices = Invioce::select('*')->where('section_id', '=', $request->section)->where('product', '=', $request->product)->get();
            $sections = Section::all();
            return view('reports.report_customer',compact('sections'))->withDetails($invoices);
        }else{
            $start_at = date($request->start_at);
            $end_at = date($request->end_at);
            $invoices = Invioce::whereBetween('invoice_date', [$start_at, $end_at])->where('section_id', '=', $request->section)->where('product', '=', $request->product)->get();
            $sections = Section::all();
            return view('reports.report_customer', compact('sections'))->withDetails($invoices);
            }
        }
    }
