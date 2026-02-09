
@if(session('success'))
    <div class="alert alert-success">
        ✔️ {{ session('success') }}
    </div>
@endif<form action="/add-product" method="POST" class="card p-3 shadow-sm">
    @csrf
    <h4>Add New Product</h4>
    <div class="mb-2">
        <label>Product Name</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-2">
        <label>Brand</label>
        <input type="text" name="brand" class="form-control" required>
    </div>
    <div class="mb-2">
        <label>Price </label>
        <input type="number" step="0.01" name="price" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Save Product</button>
</form>