<?php

namespace App\Http\Controllers;

use App\InvoicesAttachments;
use Illuminate\Http\Request;
use Auth;

class InvoicesAttachmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd( $request->pic);
        $this->validate($request, [
            'pic' => 'mimes:pdf, jpg, jpeg, png'
        ],[
            'pic.mimes' => 'صيغة الملف يجب أن تكون: pdf, jpg, jpeg, png'
        ]);
        $invoice_id = $request->invoice_id;
        $invoice_number = $request->invoice_number;


            $image = $request->file('pic');
            $file_name = $image->getClientOriginalName();
            $attachments = new InvoicesAttachments();
            $attachments->file_name = $file_name;
            $attachments->invoice_number = $invoice_number;
            $attachments->created_by = Auth::user()->name;
            $attachments->invoice_id = $invoice_id;
            $attachments->save();

            // move pic
            $imageName = $request->pic->getClientOriginalName();
            $request->pic->move(public_path('attachments/' . $invoice_number), $imageName);


        session()->flash('add_attachment', 'تم إضافة المرفق بنجاح');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\InvoicesAttachments  $invoicesAttachments
     * @return \Illuminate\Http\Response
     */
    public function show(InvoicesAttachments $invoicesAttachments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\InvoicesAttachments  $invoicesAttachments
     * @return \Illuminate\Http\Response
     */
    public function edit(InvoicesAttachments $invoicesAttachments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\InvoicesAttachments  $invoicesAttachments
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InvoicesAttachments $invoicesAttachments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\InvoicesAttachments  $invoicesAttachments
     * @return \Illuminate\Http\Response
     */
    public function destroy(InvoicesAttachments $invoicesAttachments)
    {
        //
    }
}
