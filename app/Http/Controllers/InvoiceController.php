<?php

namespace App\Http\Controllers;
use App\Invioce;
use App\User;
use App\Section;
use App\InvoicesDetails;
use App\InvoicesAttachments;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Notifications\AddInvoice;
use Illuminate\Support\Facades\Notification;
use App\Exports\InvoicesExport;
use Maatwebsite\Excel\Facades\Excel;
use AddInvoiceNotification;

class InvoiceController extends Controller
{

    public function index()
    {
        $invoices = Invioce::all();
        return view('invoices.invoices', compact('invoices'));
    }

    public function create()
    {
        $sections = Section::all();
        return view('invoices.add_invoices', compact('sections'));
    }

    public function store(Request $request){
        Invioce::create([
        'invoice_number' => $request->invoice_number,
        'invoice_date' => $request->invoice_date,
        'due_date' => $request->due_date,
        'product' => $request->product,
        'section_id' => $request->section_id,
        'amount_collection' => $request->amount_collection,
        'amount_commission' => $request->amount_commission,
        'discount' => $request->discount,
        'rate_vat' => $request->rate_vat,
        'value_vat' => $request->value_vat,
        'total' => $request->total,
        'status' => 'غير مدفوعة',
        'value_status' => 3,
        'note' => $request->note,
       ]);

       $invoice_id = Invioce::latest()->first()->id;
       InvoicesDetails::create([
        'invoice_id' => $invoice_id,
        'invoice_number' => $request->invoice_number,
        'product' => $request->product,
        'section_id' => $request->section_id,
        'status' => 'غير مدفوعة',
        'value_status' => 3,
        'note' => $request->note,
        'user' => (Auth::user()->name)
       ]);

       if ($request->hasFile('pic')) {
        $invoice_id = Invioce::latest()->first()->id;
        $image = $request->file('pic');
        $file_name = $image->getClientOriginalName();
        $invoice_number = $request->invoice_number;

        $attachments = new InvoicesAttachments();
        $attachments->file_name = $file_name;
        $attachments->invoice_number = $invoice_number;
        $attachments->created_by = Auth::user()->name;
        $attachments->invoice_id = $invoice_id;
        $attachments->save();

        // move pic
        $imageName = $request->pic->getClientOriginalName();
        $request->pic->move(public_path('attachments/' . $invoice_number), $imageName);
        }

        // $user = User::first();
        // // Notification::send($user, new AddInvoice($invoice_id));
        // $user->notify(new AddInvoice($invoice_id));

        // $user = User::get();
        $user = User::find(Auth::user()->id);
        $invoice = Invioce::latest()->first();

        // $user->notify(new \App\Notifications\AddInvoiceNotification($invoice));
        Notification::send($user, new \App\Notifications\AddInvoiceNotification($invoice));

        session()->flash('add_invoice', 'تم إضافة الفاتورة بنجاح');
        return redirect()->back();
    }


    public function edit($id)
    {
        $invoice = Invioce::where('id', $id)->first();
        $sections = Section::all();
        return view('invoices.edit-invoice', compact('invoice', 'sections'));
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $invoice = Invioce::where('id', $id)->first();
        $invoice->update([
            'invoice_number' => $request->invoice_number,
            'invoice_date' => $request->invoice_date,
            'due_date' => $request->due_date,
            'product' => $request->product,
            'section_id' => $request->section_id,
            'amount_collection' => $request->amount_collection,
            'amount_commission' => $request->amount_commission,
            'discount' => $request->discount,
            'value_vat' => $request->value_vat,
            'rate_vat' => $request->rate_vat,
            'total' => $request->total,
            'note' => $request->note,
        ]);

        session()->flash('update_invoice', 'تم تعديل الفاتورة بنجاح');
        return back();
    }

    public function destroy(Request $request)
    {
        $id = $request->invoice_id;
        $invoice = Invioce::where('id', $id)->first();
        $invoices_attachments = InvoicesAttachments::where('invoice_id', $id)->first();
        // return $invoices_attachments;
        if(!empty($invoices_attachments->invoice_number)){
            // Storage::disk('public_uploads')->delete($invoices_attachments->invoice_number. '/' . $invoices_attachments->file_name);
            Storage::disk('public_uploads')->deleteDirectory($invoices_attachments->invoice_number);
        }
        $invoice->forceDelete();

        session()->flash('invoice_delete');
        return back();

    }

    public function getProducts($id){
        $products = DB::table('products')->where('section_id', $id)->pluck('product_name', 'id');
        // return $products;
        return json_encode($products);
    }

    public function editStatus($id)
    {
        $invoice = Invioce::where('id', $id)->first();
        return view('invoices.edit_status', compact('invoice'));
    }

    public function updateStatus(Request $request, $id){
        $invocie_id = $id;
        $invoice = Invioce::where('id', $id)->first();
        $invoice_details = InvoicesDetails::where('invoice_id', $id)->first();

        if($request->status == "مدفوعة كاملة"){
            $invoice->update([
                'value_status' => 1,
                'status' => $request->status,
            ]);
            InvoicesDetails::create([
                'invoice_id' => $invocie_id,
                'section_id' => $request->section_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'status' => $request->status,
                'value_status' => 1,
                'payment_date' => $request->payment_date,
                'note' => $request->note,
                'user' => (Auth::user()->name)
            ]);

        }else{
            $invoice->update([
                'value_status' => 2,
                'status' => $request->status,
            ]);
            InvoicesDetails::create([
                'invoice_id' => $invocie_id,
                'section_id' => $request->section_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'status' => $request->status,
                'value_status' => 2,
                'payment_date' => $request->payment_date,
                'note' => $request->note,
                'user' => (Auth::user()->name)
            ]);
        }

        session()->flash('update_status');
        return redirect('/invoices');
    }

    public function paidInvoices(){
        $paidInvoices = Invioce::where('value_status', '=', 1)->get();
        return view('invoices.piad_invoices', compact('paidInvoices'));
    }

    public function unpaidInvoices(){
        $unpaidInvoices = Invioce::where('value_status', '=', 3)->get();
        return view('invoices.unpaid_invoices', compact('unpaidInvoices'));
    }

    public function partiallyPaidInvoices(){
        $partiallyPaidInvoices = Invioce::where('value_status', '=', 2)->get();
        return view('invoices.partially_paid_invoices', compact('partiallyPaidInvoices'));
    }

    public function invoicesDestroy(Request $request){
        $id = $request->invoice_id;
        $invoice = Invioce::where('id', $id)->first();
        $invoice->delete();
        session()->flash('invoiec_archived');
        return back();
    }

    public function invoicesArchives(){
        $invoicesTrashed = Invioce::onlyTrashed()->get();
        return view('invoices.archive_invoiecs', compact('invoicesTrashed'));
    }

    public function unarchiveInvoice(Request $request){
        $id = $request->invoice_id;

        $invoice = Invioce::withTrashed()->where('id', $id)->restore();
        session()->flash('restored_invoice');
        return back();
    }

    public function printInvoice($id){
        $invoice = Invioce::where('id', $id)->first();
        return view('invoices.print_invoice', compact('invoice'));
    }

    // Export Excel
    public function export()
    {
        return \Excel::download(new InvoicesExport, 'قائمة الفواتير.xlsx');
    }

    public function makeAllRead (Request $request)
    {

        $userUnreadNotification= auth()->user()->unreadNotifications;

        if($userUnreadNotification) {
            $userUnreadNotification->markAsRead();
            return back();
        }


    }
}
