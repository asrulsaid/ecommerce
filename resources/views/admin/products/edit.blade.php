@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">{{ __('Edit Product') }}</div>

                <div class="card-body">
                    <a href="{{ route('products.index') }}" class="btn btn-primary mb-3">Back</a>
                    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                          <label for="name" class="form-label">Name</label>
                          <input name="name" value="{{ $product->name }}" type="text" class="form-control" id="name">
                        </div>
                        <div class="mb-3">
                          <label for="sku" class="form-label">SKU</label>
                          <input name="sku" value="{{ $product->sku }}" type="text" class="form-control" id="sku">
                        </div>
                        <div class="mb-3">
                          <label for="price" class="form-label">Price</label>
                          <input name="price" value="{{ $product->price }}" type="number" class="form-control" id="price">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" class="form-control" id="description" rows="3">{{ $product->description }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input name="image" class="form-control" type="file" id="image">
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
