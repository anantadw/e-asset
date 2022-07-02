@extends('layouts.app')

@push('css-libraries')
    <link rel="stylesheet" href="{{ asset('node_modules/izitoast/dist/css/iziToast.min.css') }}">
@endpush

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Beranda</h1>
    </div>
    <div class="section-body">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-6 text-center p-5">
                    <p>E-Asset merupakan aplikasi yang digunakan untuk mengelola data inventaris yang meliputi penerimaan barang, pendistribusian barang, permintaan barang, dan pengembalian barang.</p>
                    <p>E-Asset memungkinkan kita untuk melacak produk berdasarkan transaksi barang atau jenis transaksi.</p>
                    <p>E-Asset juga dapat digunakan untuk mencetak setiap transaksi dan membantu dalam mengganti stok barang yang rusak.</p>
                    <a href="{{ route('user-request') }}" class="btn btn-primary btn-lg mt-3"><i class="fas fa-boxes mr-2"></i>Pinjam Barang Sekarang</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('js-libraries')
    <script src="{{ asset('node_modules/izitoast/dist/js/iziToast.min.js') }}"></script>
@endpush

@push('scripts')
    @if (session()->has('password_alert'))
        <script>
            $(document).ready(function () { 
                iziToast.warning({
                    message: 'Anda disarankan untuk mengganti kata sandi!',
                });
            });
        </script>
    @endif
@endpush