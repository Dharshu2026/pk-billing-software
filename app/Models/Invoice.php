<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model {
protected $fillable = ['bill_number', 'customer_name', 'bill_date', 'total_amount'];
    public function items() {
        return $this->hasMany(InvoiceItem::class);
    }
}