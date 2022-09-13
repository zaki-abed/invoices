<?php

namespace App\Http\Controllers;

use App\InvoicesDetails;
use App\Invioce;
use App\InvoicesAttachments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class InvoicesDetailsController extends Controller
{

    public function show($invoice_id)
    {
        $invoice = Invioce::where('id', $invoice_id)->first();
        $invoicesDetails = InvoicesDetails::where('invoice_id', $invoice_id)->get();
        $invoiceAttachments = InvoicesAttachments::where('invoice_id', $invoice_id)->get();
        // return $invoiceAttachments;
        // dd(4invoiceAttachments);
        return view('invoices.invoice-details', compact('invoice', 'invoicesDetails', 'invoiceAttachments'));
    }

    public function openFile($invoice_number, $file_name){
        $file = Storage::disk('public_uploads')->getDriver()->getAdapter()->applyPathPrefix($invoice_number. '/' .$file_name);
        return response()->file($file);
    }

    public function getFile($invoice_number, $file_name){
        $content = Storage::disk('public_uploads')->getDriver()->getAdapter()->applyPathPrefix($invoice_number. '/' .$file_name);
        return response()->download($content);
    }

    public function destroy(Request $request){
        $invoiceAttachments = InvoicesAttachments::find($request->id_file);
        $invoiceAttachments->delete();
        Storage::disk('public_uploads')->delete($request->invoice_number. '/' .$request->file_name);
        session()->flash('delete_file', 'تم الحذف بنجاح');
        return back();
    }
}
