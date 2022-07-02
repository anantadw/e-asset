@extends('layouts.app')

@push('css-libraries')
    <link rel="stylesheet" href="{{ asset('node_modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('node_modules/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}">
@endpush

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Transaksi Masuk</h1>
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
                                            <th scope="col">Admin</th>
                                            <th scope="col">Nama Barang</th>
                                            <th scope="col">Kategori</th>
                                            <th scope="col">Total Stok</th>
                                            <th scope="col">Waktu</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($transactions as $no => $transaction)
                                            <tr>
                                                <th scope="row" class="align-middle">{{ ++$no }}</th>
                                                <td class="align-middle">{{ $transaction->admin_name }}</td>
                                                <td class="align-middle">{{ $transaction->item_name }}</td>
                                                <td class="align-middle">{{ $transaction->item_category }}</td>
                                                <td class="align-middle">{{ $transaction->item_stock }}</td>
                                                <td class="align-middle">
                                                    {{ $transaction->created_at->locale('id')->isoFormat('dddd, DD MMMM YYYY') }}
                                                    <br>
                                                    {{ $transaction->created_at->locale('id')->isoFormat('HH:mm:ss') }}
                                                </td>
                                                <td class="align-middle">
                                                    @if ($transaction->status === '1')
                                                        <span class="badge badge-success">Tambah</span>
                                                    @elseif ($transaction->status === '2')
                                                        <span class="badge badge-warning">Perbarui</span>
                                                    @else
                                                        <span class="badge badge-danger">Hapus</span>
                                                    @endif
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
        });
    </script>
@endpush