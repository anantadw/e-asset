@extends('layouts.app')

@push('css-libraries')
    <link rel="stylesheet" href="{{ asset('node_modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('node_modules/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}">
@endpush

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Keranjang</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{ route('user-request') }}">Pinjam Barang</a></div>
            <div class="breadcrumb-item">Keranjang</div>
        </div>
    </div>
    <div class="section-body">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-around align-items-center pb-0">
                            <h6 class="mb-0">Nama: {{ session()->get('user_name') }}</h6>
                            <h6 class="mb-0">Nomor Induk: {{ $user_unique_code }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered text-center" id="data-table">
                                    <thead>
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Nama Barang</th>
                                            <th scope="col">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($carts as $no => $cart)
                                            <tr>
                                                <th scope="row" class="align-middle">{{ ++$no }}</th>
                                                <td class="align-middle"><h6>{{ $cart->itemDetail->codename }}</h6></td>
                                                <td class="align-middle">
                                                    <form action="{{ route('user-remove-cart') }}" method="post" class="form-request">
                                                        @method('delete')
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $cart->id }}">
                                                        <button type="submit" class="btn btn-danger"><i class="fas fa-trash mr-2"></i>Batal Pinjam</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <a href="{{ route('user-request') }}" class="btn btn-outline-secondary btn-lg"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
                            <form action="{{ route('user-request-store') }}" method="post" class="form-request">
                                @csrf
                                @foreach ($carts as $cart)
                                    <input type="hidden" name="carts[]" value="{{ $cart->item_detail_id }}">
                                @endforeach
                                <button type="submit" class="btn btn-success btn-lg @if ($carts->isEmpty()) disabled @endif" @if ($carts->isEmpty()) disabled @endif><i class="fas fa-clipboard-list mr-2"></i>Pesan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('js-libraries')
    <script src="{{ asset('node_modules/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('node_modules/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('node_modules/datatables.net-select-bs4/js/select.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('node_modules/sweetalert/dist/sweetalert.min.js') }}"></script>
@endpush

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#data-table').DataTable({
                "language": {
                    "emptyTable": "Tidak ada data",
                    "lengthMenu": "Menampilkan _MENU_ data per halaman",
                    "zeroRecords": "Data tidak ditemukan",
                    "info": "Menampilkan halaman ke _PAGE_ dari _PAGES_",
                    "infoEmpty": "Tidak ada data",
                    "infoFiltered": "(Difilter dari _MAX_ data)",
                    "search": "Cari:",
                    "paginate": {
                        "first": "Awal",
                        "last": "Akhir",
                        "next": "&gt",
                        "previous": "&lt"
                    },
                }
            });

            $('.btn-success, .btn-danger').on('click', function () {
                $(this).addClass('btn-progress');
            });
        });
    </script>
@endpush