<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Shop;

class InvoiceController extends Controller {
public function dashboard()
{
    // Inaikku evvalavu sales aayirukku nu calculate pannuvom
    $todaySales = Invoice::whereDate('created_at', now())->sum('total_amount');
    
    // Motham evvalavu bills potturukkom nu count pannuvom
    $totalBills = Invoice::count();
    
    // Stock 10-kku kuraivaa irukkira items-ai alert-kaga edukkom
    $lowStockItems = Product::where('stock', '<', 10)->get();
    
    // Indha data-vai Dashboard view-ku anuprom
    return view('dashboard.index', compact('todaySales', 'totalBills', 'lowStockItems'));
}
   public function index() {
    // Database-la irukka bills-ai order panni edukkum
    $invoices = Invoice::orderBy('id', 'desc')->get();
    return view('history.index', compact('invoices')); 
}

public function edit($id) {
    // Specific ID vachu bill-ai thedurom
    $invoice = Invoice::with('items')->findOrFail($id);
    return view('billing.edit', compact('invoice'));
}

    // 2. New Bill Form
    public function create() {
        $shops = Shop::all();
        $products = Product::all(); 
        return view('billing.index', compact('shops', 'products'));
    }

   
public function update(Request $request, $id) {
    $invoice = Invoice::findOrFail($id);
    $newTotalAmount = 0; // Pudhu total calculate panna indha variable thevai

    // 1. Pazhaya stock-ai thirumba sethuduvom (Reverse Stock)
    foreach ($invoice->items as $oldItem) {
        $product = Product::where('name', $oldItem->item_name)->first();
        if ($product) {
            $product->increment('stock', $oldItem->quantity);
        }
    }

    // 2. Pazhaya items-ai delete pannuvom
    $invoice->items()->delete();

    // 3. Pudhu items-ai save panni, New Total calculate pannuvom
    foreach ($request->items as $item) {
        if (!empty($item['name']) && isset($item['qty'])) {
            $amount = $item['qty'] * $item['rate'];
            $newTotalAmount += $amount; // Ovvoru item amount-aiyum koottuvom

            $invoice->items()->create([
                'item_name' => $item['name'],
                'quantity' => $item['qty'],
                'price' => $item['rate'],
                'amount' => $amount,
            ]);

            // Pudhu stock-ai kuraippom
            $product = Product::where('name', $item['name'])->first();
            if ($product) {
                $product->decrement('stock', $item['qty']);
            }
        }
    }

    // 4. Main Invoice-la Pudhu Total matrum Customer Name-ai update panrom
    $invoice->update([
        'total_amount' => $newTotalAmount,
        'customer_name' => $request->customer_name
    ]);

    return redirect()->route('invoices.index')->with('success', 'Bill & Total Updated Successfully!');
}
public function modifyIndex(Request $request)
{
    $query = Invoice::orderBy('created_at', 'desc');

    // Customer name vachu theda filter
    if ($request->has('search')) {
        $query->where('customer_name', 'like', '%' . $request->search . '%');
    }

    $invoices = $query->get();
    return view('history.index', compact('invoices'));
}
    // 6. Save New Bill with Stock Deduction
  public function store(Request $request) {
    // 1. Invoice Create (Adhae logic dhaan)
    $invoice = Invoice::create([
        'bill_number' => Invoice::latest('id')->first() ? Invoice::latest('id')->first()->bill_number + 1 : 2000,
        'customer_name' => $request->customer_name,
        'bill_date' => now(),
      'total_amount' => $request->total_amount ?? 0,
    ]);

    foreach ($request->items as $item) {
        if (!empty($item['name'])) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'item_name'  => $item['name'],
                'unit'       => $item['unit'] ?? 'Pcs',
                'quantity'   => $item['qty'] ?? 0,
                'price'      => $item['rate'] ?? 0,
                'amount'     => ($item['qty'] ?? 0) * ($item['rate'] ?? 0),
            ]);

            $product = Product::where('name', $item['name'])->first();
            if ($product) {
                $product->decrement('stock', $item['qty']);
            }
        }
    }

  return redirect()->route('invoices.create')
        ->with('success', 'Bill Saved!')
        ->with('print_id', $invoice->id); // Indha ID dhaan print panna help pannum
}


public function destroyProduct($id) {
    $product = Product::findOrFail($id);
    $product->delete();
    return back()->with('success', 'Item Deleted Successfully!');
}

    // 7. Shop & Product Management
    public function storeShop(Request $request) {
        Shop::updateOrCreate(
            ['id' => $request->shop_id],
            ['shop_name' => $request->shop_name, 'address' => $request->address, 'phone' => $request->phone]
        );
        return back()->with('success', 'Successfully Saved!');
    }

    public function storeProduct(Request $request) {
        Product::updateOrCreate(
            ['id' => $request->product_id],
            ['name' => $request->name, 'price' => $request->price, 'unit' => $request->unit ?? 'Kgs']
        );
        return back()->with('success', 'Successfully Saved!');
    }

    // 8. Print Invoice
    public function show($id) {
        $invoice = Invoice::with('items')->findOrFail($id);
        return view('billing.print', compact('invoice'));
    }
}