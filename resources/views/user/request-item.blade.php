@extends('layouts.app')

@push('css-libraries')
    <link rel="stylesheet" href="{{ asset('node_modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('node_modules/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}">
@endpush

@section('content')
<section class="section">
    <div class="section-header">
        <h1>{{ $item_name }}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{ route('user-request') }}">Pinjam Barang</a></div>
            <div class="breadcrumb-item">{{ $item_name }}</div>
        </div>
        <a href="{{ route('user-request-cart') }}" class="btn btn-primary ml-auto"><i class="fas fa-shopping-cart mr-2"></i>Keranjang<span class="badge badge-transparent ml-2">{{ $total_cart }}</span></a>
    </div>
    <div class="section-body">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered text-center" id="data-table">
                                    <thead>
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Nama dan Kode Barang</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($item_details as $no => $item_detail)
                                            <tr>
                                                <th scope="row" class="align-middle">{{ ++$no }}</th>
                                                <td class="align-middle"><h6>{{ $item_detail->codename }}</h6></td>
                                                <td class="align-middle">
                                                    @if ($item_detail->status === '1')
                                                        <span class="badge badge-success">Tersedia</span>
                                                    @elseif ($item_detail->status === '2')
                                                        <span class="badge badge-warning">Dipinjam</span>
                                                    @else
                                                        <span class="badge badge-danger">Rusak</span>
                                                    @endif
                                                </td>
                                                <td class="align-middle">
                                                    <form action="{{ route('user-add-cart', ['slug' => $item_slug]) }}" method="post" class="form-request">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $item_detail->id }}">
                                                        <button type="submit" class="btn btn-primary @if ($item_detail->status !== '1') disabled @endif" @if ($item_detail->status !== '1') disabled @endif><i class="fas fa-cart-plus mr-2"></i>Pinjam</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
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

            $('button[type="submit"]').on('click', function () {
                $(this).addClass('btn-progress');
            });
        });
    </script>
@endpush