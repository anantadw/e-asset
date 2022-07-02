@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Ubah Barang / {{ $item->name }}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{ route('admin-index') }}">Dasbor</a></div>
            <div class="breadcrumb-item">Ubah Barang</div>
        </div>
    </div>
    <div class="section-body">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-8">
                    <div class="card">
                        <div class="card-body pb-0">
                            <form action="{{ route('admin-item-update', ['item' => $item->slug]) }}" method="post" id="form-edit-item">
                                @method('patch')
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="name">Nama Barang</label>
                                        <input type="text" class="form-control" id="name" name="name" value="{{ $item->name }}">
                                        <div class="invalid-feedback" id="name_error"></div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="category">Kategori</label>
                                        <select class="form-control" id="category" name="category">
                                            <option value="">-</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" @if ($item->category_id === $category->id) selected @endif>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback" id="category_error"></div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="stock">Stok</label>
                                        <input type="number" class="form-control" id="stock" min="0" value="{{ $item->stock }}" readonly>
                                        <div class="invalid-feedback" id="stock_error"></div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-warning" form="form-edit-item"><i class="fas fa-edit mr-2"></i>Ubah</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('js-libraries')
    <script src="{{ asset('node_modules/sweetalert/dist/sweetalert.min.js') }}"></script>
@endpush