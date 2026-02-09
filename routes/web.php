<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\AuthController;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Invoice;
use App\Models\InvoiceItem;

/*
|--------------------------------------------------------------------------
| 1. Public Routes (Login)
|--------------------------------------------------------------------------
*/

// Browser open panna udanae login-ku redirect aaganum
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| 2. Protected Routes (Login Panna Mattumae Work Aagum)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [InvoiceController::class, 'dashboard'])->name('dashboard');

    // Billing & Invoices
    Route::get('/billing/create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('/invoices/store', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('/history', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoice/{id}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/billing/modify', [InvoiceController::class, 'modifyIndex'])->name('invoices.modify');
    Route::get('/billing/edit/{id}', [InvoiceController::class, 'edit'])->name('invoices.edit');
    Route::put('/invoices/update/{id}', [InvoiceController::class, 'update'])->name('invoices.update');
    Route::delete('/history/{id}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');

    // Inventory / Product Management
    Route::get('/inventory', function () {
        $products = Product::all();
        return view('inventory.index', compact('products'));
    })->name('inventory.index');


Route::get('/inventory/add', function () {
    return view('inventory.create');
})->name('products.create'); 

Route::get('/inventory/create', [InvoiceController::class, 'createProduct'])->name('products.create');

    Route::post('/products/store', function (Request $request) {
        Product::updateOrCreate(
            ['name' => $request->name],
            ['price' => $request->price, 'unit' => $request->unit, 'stock' => $request->stock ?? 100]
        );
        return back()->with('success', 'Successfully Added!');
    })->name('products.store');

    Route::put('/products/{id}', function (Request $request, $id) {
        Product::findOrFail($id)->update([
            'name' => $request->name,
            'unit' => $request->unit,
            'price' => $request->price,
        ]);
        return back()->with('success', 'Item updated successfully!');
    })->name('products.update');

    Route::delete('/products/{id}', [InvoiceController::class, 'destroyProduct'])->name('products.destroy');

    // Shop Management
    Route::get('/shops', function () {
        $shops = Shop::all();
        return view('shops.index', compact('shops'));
    })->name('shops.index');

    Route::post('/shops/store', function (Request $request) {
        Shop::updateOrCreate(
            ['shop_name' => $request->shop_name],
            ['address' => $request->address, 'phone' => $request->phone]
        );
        return back()->with('success', 'Successfully Added!');
    })->name('shops.store');

    Route::put('/shops/{id}', function (Request $request, $id) {
        Shop::findOrFail($id)->update([
            'shop_name' => $request->shop_name,
            'address' => $request->address,
            'phone' => $request->phone,
        ]);
        return back()->with('success', 'Shop updated successfully!');
    })->name('shops.update');
});

/*
|--------------------------------------------------------------------------
| 3. Import Routes (Utility)
|--------------------------------------------------------------------------
*/

Route::get('/import-items', function () {
    $file = storage_path('app/items.csv');
    if (!file_exists($file)) return "items.csv missing!";
    $handle = fopen($file, "r");
    fgetcsv($handle); fgetcsv($handle); // Skip headers
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        if(!empty($data[0])) {
            Product::create(['name' => trim($data[0]), 'price' => (float)($data[3] ?? 0), 'stock' => (int)($data[4] ?? 100)]);
        }
    }
    fclose($handle);
    return "Items Imported!";
});

Route::get('/import-shops', function () {
    $file = storage_path('app/shops.csv');
    if (!file_exists($file)) return "shops.csv missing!";
    $handle = fopen($file, "r");
    fgetcsv($handle);
    while (($data = fgetcsv($handle, 1000, ",")) !== false) {
        if (!empty($data[0])) {
            Shop::updateOrCreate(['shop_name' => trim($data[0])], ['address' => $data[2] ?? '']);
        }
    }
    fclose($handle);
    return "Shops Imported!";
});