@extends('layouts.app')

@push('css-libraries')
    <link rel="stylesheet" href="{{ asset('node_modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('node_modules/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}">
@endpush

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Transaksi Keluar / Disetujui</h1>
    </div>
    <div class="section-body">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-primary alert-has-icon">
                        <div class="alert-icon"><i class="far fa-lightbulb"></i></div>
                        <div class="alert-body">
                            <div class="alert-title mb-0">Perhatian: pindai barang kemudian pindai pengguna.</div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-tabs justify-content-center" id="tab-links">
                                <li class="nav-item">
                                    <a class="nav-link" id="pending-tab" href="{{ route('admin-outgoing-transaction-pending') }}" aria-controls="pending" aria-selected="false"><i class="fas fa-clock text-warning mr-2"></i>Tertunda</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" id="approved-tab" href="{{ route('admin-outgoing-transaction-approved') }}" aria-controls="approved" aria-selected="true"><i class="fas fa-check-circle text-success mr-2"></i>Disetujui</a>
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
                                                            @if ($item->is_scanned === 1)
                                                                <li>{{ $item->itemDetail->codename }}<i class="fas fa-check ml-1 text-success"></i></li>
                                                            @else
                                                                <li><a href="#" class="btn-scan-item" data-toggle="modal" data-target="#modal-scan-item" data-id="{{ $item->item_detail_id }}" data-codename="{{ $item->itemDetail->codename }}" data-transaction="{{ $item->transaction_id }}">{{ $item->itemDetail->codename }}</a></li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                </td>
                                                <td class="align-middle">
                                                    {{ $transaction->updated_at->locale('id')->isoFormat('dddd, DD MMMM YYYY') }}
                                                    <br>
                                                    {{ $transaction->updated_at->locale('id')->isoFormat('HH:mm:ss') }}
                                                </td>
                                                <td class="align-middle">
                                                    <button type="button" class="btn btn-success btn-scan-user @if ($transaction->items_are_scanned === 0) disabled @endif" data-toggle="modal" data-target="#modal-scan-user" data-id="{{ $transaction->user->id }}" data-user="{{ $transaction->user->name }}" data-transaction="{{ $transaction->id }}" @if ($transaction->items_are_scanned === 0) disabled @endif><i class="fas fa-eye mr-2"></i>Pindai Pengguna</button>
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
<div class="modal fade" tabindex="-1" role="dialog" id="modal-scan-item" aria-modal="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pindai Kode Unik Barang: <span id="item_name"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin-outgoing-transaction-approved-scan', ['action' => 'item']) }}" method="post" id="form-scan-item">
                    @csrf
                    <input type="hidden" name="item_id" id="item_id" value="">
                    <input type="hidden" name="item_transaction_id" id="item_transaction_id" value="">
                    <input type="number" class="form-control" id="item_unique_code" name="item_unique_code" autofocus>
                    <div class="invalid-feedback" id="item_unique_code_error"></div>
                </form>
            </div>
            <div class="modal-footer bg-whitesmoke">
                <button type="submit" class="btn btn-success" form="form-scan-item" id="btn-submit-item"><i class="fas fa-paper-plane mr-2"></i>Kirim</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="modal-scan-user" aria-modal="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pindai Kode Unik Pengguna: <span id="user_name"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin-outgoing-transaction-approved-scan', ['action' => 'user']) }}" method="post" id="form-scan-user">
                    @csrf
                    <input type="hidden" name="user_id" id="user_id" value="">
                    <input type="hidden" name="user_transaction_id" id="user_transaction_id" value="">
                    <input type="number" class="form-control" id="user_unique_code" name="user_unique_code" autofocus>
                    <div class="invalid-feedback" id="user_unique_code_error"></div>
                </form>
            </div>
            <div class="modal-footer bg-whitesmoke">
                <button type="submit" class="btn btn-success" form="form-scan-user"><i class="fas fa-paper-plane mr-2"></i>Kirim</button>
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

            $('.btn-scan-item').tooltip({
                title: 'Pindai kode unik barang'
            });

            $('.btn-scan-user').tooltip({
                title: 'Pindai kode unik pengguna'
            });

            $('button[type="submit"]').on('click', function () {
                $(this).addClass('btn-progress');
            });
        });
    </script>
@endpush