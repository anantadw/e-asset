@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Pinjam Barang</h1>
        <a href="{{ route('user-request-cart') }}" class="btn btn-primary ml-auto"><i class="fas fa-shopping-cart mr-2"></i>Keranjang<span class="badge badge-transparent ml-2">{{ $total_cart }}</span></a>
    </div>
    <div class="section-body">
        <div class="container">
            <div class="row justify-content-center">
                @foreach ($items as $item)
                    <div class="col-12 col-sm-12 col-md-8 col-lg-6">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h4>{{ $item->name }}</h4>
                                <span class="badge badge-light mr-2">{{ $item->category->name }}</span>
                                <span class="badge badge-light">{{ $item->stock }}</span>
                                <a href="{{ route('user-request-item', ['item' => $item->slug]) }}" class="btn btn-primary ml-auto"><i class="fab fa-ethereum mr-2"></i>Pilih</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endsection