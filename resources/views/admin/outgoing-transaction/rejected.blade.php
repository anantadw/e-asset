@extends('layouts.app')

@push('css-libraries')
    <link rel="stylesheet" href="{{ asset('node_modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('node_modules/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}">
@endpush

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Transaksi Keluar / Ditolak</h1>
    </div>
    <div class="section-body">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-tabs justify-content-center" id="tab-links">
                                <li class="nav-item">
                                    <a class="nav-link" id="pending-tab" href="{{ route('admin-outgoing-transaction-pending') }}" aria-controls="pending" aria-selected="false"><i class="fas fa-clock text-warning mr-2"></i>Tertunda</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="approved-tab" href="{{ route('admin-outgoing-transaction-approved') }}" aria-controls="approved" aria-selected="false"><i class="fas fa-check-circle text-success mr-2"></i>Disetujui</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="ongoing-tab" href="{{ route('admin-outgoing-transaction-ongoing') }}" aria-controls="ongoing" aria-selected="false"><i class="fas fa-hourglass-half text-primary mr-2"></i></i>Sedang dipinjam</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="completed-tab" href="{{ route('admin-outgoing-transaction-completed') }}" aria-controls="completed" aria-selected="false"><i class="fas fa-calendar-check text-success mr-2"></i>Selesai</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" id="rejected-tab" href="{{ route('admin-outgoing-transaction-rejected') }}" aria-controls="rejected" aria-selected="true"><i class="fas fa-times-circle text-danger mr-2"></i>Ditolak</a>
                                </li>
                            </ul>
                            <div class="table-responsive mt-3">
                                <table class="table table-hover table-bordered text-center" id="data-table">
                                    <thead>
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Faktur</th>
                                            <th scope="col">Pengguna</th>
                                            <th scope="col">Barang</th>
                                            <th scope="col">Waktu</th>
                                            <th scope="col">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($transactions as $no => $transaction)
                                            <tr>
                                                <th scope="row" class="align-middle">{{ ++$no }}</th>
                                                <td class="align-middle">{{ $transaction->invoice }}</td>
                                                <td class="align-middle">{{ $transaction->user->name }}</td>
                                                <td class="align-middle">
                                                    <ul class="mb-0">
                                                        @foreach ($transaction->detailTransactions as $item)
                                                            <li>{{ $item->itemDetail->codename }}</li>
                                                        @endforeach
                                                    </ul>
                                                </td>
                                                <td class="align-middle">
                                                    {{ $transaction->updated_at->locale('id')->isoFormat('dddd, DD MMMM YYYY') }}
                                                    <br>
                                                    {{ $transaction->updated_at->locale('id')->isoFormat('HH:mm:ss') }}
                                                </td>
                                                <td class="align-middle">{{ $transaction->description }}</td>
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

            $('[role="tab"]').on('click', function () {
                const link = $(this).attr('aria-controls');
            });
        });
    </script>
@endpush