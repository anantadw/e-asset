@extends('layouts.app')

@push('css-libraries')
    <link rel="stylesheet" href="{{ asset('node_modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('node_modules/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}">
@endpush

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Transaksi Keluar / Tertunda</h1>
    </div>
    <div class="section-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-tabs justify-content-center" id="tab-links">
                                <li class="nav-item">
                                    <a class="nav-link active" id="pending-tab" href="{{ route('admin-outgoing-transaction-pending') }}" aria-controls="pending" aria-selected="true"><i class="fas fa-clock text-warning mr-2"></i>Tertunda</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="approved-tab" href="{{ route('admin-outgoing-transaction-approved') }}" aria-controls="approved" aria-selected="false"><i class="fas fa-check-circle text-success mr-2"></i>Disetujui</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="ongoing-tab" href="{{ route('admin-outgoing-transaction-ongoing') }}" aria-controls="ongoing" aria-selected="false"><i class="fas fa-hourglass-half text-primary mr-2"></i></i>Berlangsung</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="completed-tab" href="{{ route('admin-outgoing-transaction-completed') }}" aria-controls="completed" aria-selected="false"><i class="fas fa-calendar-check text-success mr-2"></i>Selesai</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="rejected-tab" href="{{ route('admin-outgoing-transaction-rejected') }}" aria-controls="rejected" aria-selected="false"><i class="fas fa-times-circle text-danger mr-2"></i>Ditolak</a>
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
                                            <th scope="col">Aksi</th>
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
                                                    {{ $transaction->created_at->locale('id')->isoFormat('dddd, DD MMMM YYYY') }}
                                                    <br>
                                                    {{ $transaction->created_at->locale('id')->isoFormat('HH:mm:ss') }}
                                                </td>
                                                <td class="align-middle">
                                                    <form action="{{ route('admin-outgoing-transaction-pending-approve') }}" method="post" class="form-approve mb-2">
                                                        @method('put')
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $transaction->id }}">
                                                        <button type="submit" class="btn btn-success"><i class="fas fa-check-circle mr-2"></i>Setujui</button>
                                                    </form>
                                                    <button type="button" class="btn btn-danger btn-reject" data-toggle="modal" data-target="#modal-delete" data-id="{{ $transaction->id }}" data-invoice="{{ $transaction->invoice }}" data-user="{{ $transaction->user->name }}"><i class="fas fa-times-circle mr-2"></i>Tolak</button>
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
<div class="modal fade" tabindex="-1" role="dialog" id="modal-delete" aria-modal="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tolak Pesanan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin-outgoing-transaction-pending-reject') }}" method="post" id="form-reject">
                    @method('put')
                    @csrf
                    <input type="hidden" name="id" id="id" value="">
                    <div class="form-row mb-3">
                        <div class="col">
                            <label for="invoice">Faktur</label>
                            <input type="text" class="form-control" id="invoice" value="" readonly>
                        </div>
                        <div class="col">
                            <label for="user">Pengguna</label>
                            <input type="text" class="form-control" id="user" value="" readonly>
                        </div>
                    </div>
                    <label for="description">Keterangan</label>
                    <textarea name="description" id="description" cols="30" rows="5" class="form-control" placeholder="Alasan penolakan"></textarea>
                    <div class="invalid-feedback" id="description_error"></div>
                </form>
            </div>
            <div class="modal-footer bg-whitesmoke">
                <button type="submit" class="btn btn-danger" form="form-reject"><i class="fas fa-times-circle mr-2"></i>Tolak</button>
            </div>
        </div>
    </div>
</div>
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

            $('.btn-success, button[form="form-reject"]').on('click', function () {
                $(this).addClass('btn-progress');
            });
        });
    </script>
@endpush