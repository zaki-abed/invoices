<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoicesDetails extends Model
{
    protected $fillable = [
        'invoice_id',
        'invoice_number',
        'product',
        'section_id',
        'status',
        'value_status',
        'note',
        'user',
        'payment_date'
    ];

    public function section(){
        return $this->belongsTo('App\Section');
    }
    public function invoice(){
        return $this->belongsTo('App\Invioce');
    }
}
