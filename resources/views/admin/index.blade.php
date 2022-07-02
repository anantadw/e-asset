@extends('layouts.app')

@push('css-libraries')
    <link rel="stylesheet" href="{{ asset('node_modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('node_modules/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('node_modules/izitoast/dist/css/iziToast.min.css') }}">
@endpush

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Dasbor</h1>
    </div>
    <div class="section-body">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="far fa-user"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Jumlah Pengguna</h4>
                            </div>
                            <div class="card-body">
                                {{ $total_users }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger">
                            <i class="fas fa-cubes"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Jumlah Barang</h4>
                            </div>
                            <div class="card-body">
                                {{ $total_items }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Transaksi Masuk</h4>
                            </div>
                            <div class="card-body">
                                {{ $incoming_transactions }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Transaksi Keluar</h4>
                            </div>
                            <div class="card-body">
                                {{ $outgoing_transactions }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-whitesmoke">
                            <form action="{{ route('admin-export') }}" method="post">
                                @csrf
                                <button type="submit" class="btn btn-primary"><i class="fas fa-download mr-2"></i>Unduh Laporan</button>
                            </form>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered text-center" id="data-table">
                                    <thead>
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Nama Barang</th>
                                            <th scope="col">Kategori</th>
                                            <th scope="col">Sisa Stok</th>
                                            <th scope="col">Didaftarkan Oleh</th>
                                            <th scope="col">Waktu</th>
                                            <th scope="col">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($items as $no => $item)
                                            <tr>
                                                <th scope="row" class="align-middle">{{ ++$no }}</th>
                                                <td class="align-middle"><h6>{{ $item->name }}</h6></td>
                                                <td class="align-middle">{{ $item->category->name }}</td>
                                                <td class="align-middle">{{ $item->stock }}</td>
                                                <td class="align-middle">{{ $item->user->name }}</td>
                                                <td class="align-middle">
                                                    {{ $item->created_at->locale('id')->isoFormat('dddd, DD MMMM YYYY') }}
                                                    <br>
                                                    {{ $item->created_at->locale('id')->isoFormat('HH:mm:ss') }}
                                                </td>
                                                <td class="align-middle">
                                                    <a href="{{ route('admin-item-detail', ['item' => $item->slug]) }}" class="btn btn-icon btn-info"><i class="fas fa-info-circle"></i></a>
                                                    {{-- <a href="{{ route('admin-item-edit', ['item' => $item->slug]) }}" class="btn btn-icon btn-warning"><i class="far fa-edit"></i></a> --}}
                                                    <button type="button" class="btn btn-icon btn-danger btn-delete" data-toggle="modal" data-target="#modal-delete" data-id="{{ $item->id }}" data-name="{{ $item->name }}"><i class="fas fa-trash"></i></button>
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
                <h5 class="modal-title">Hapus Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                Anda yakin ingin menghapus <span class="font-weight-bold" id="name-delete"></span>?
            </div>
            <div class="modal-footer bg-whitesmoke">
                <form action="{{ route('admin-item-delete') }}" method="post" id="form-delete-item">
                    @method('delete')
                    @csrf
                    <input type="hidden" name="id" id="id" value="">
                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash mr-2"></i>Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@if (session('success'))
    <div id="flash-data" data-flashdata="{{ session('success') }}"></div>
@endif
@endsection

@push('js-libraries')
    <script src="{{ asset('node_modules/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('node_modules/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('node_modules/datatables.net-select-bs4/js/select.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('node_modules/sweetalert/dist/sweetalert.min.js') }}"></script>
    <script src="{{ asset('node_modules/izitoast/dist/js/iziToast.min.js') }}"></script>
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
        
            $('.btn-info').tooltip({
                title: 'Detail'
            });
            $('.btn-warning').tooltip({
                title: 'Ubah'
            });
            $('.btn-delete').tooltip({
                title: 'Hapus'
            });

            $('.btn-primary').on('click', function () {
                $(this).addClass('btn-progress');
                setTimeout(() => {
                    $(this).removeClass('btn-progress');
                }, 2000);
            });

            const flashdata = $('#flash-data').data('flashdata');
            if (flashdata) {
                swal({
                    title: "Berhasil",
                    text: flashdata,
                    icon: "success",
                    timer: 1500,
                    buttons: false
                });
            }
        });
    </script>
    @if (session()->has('password_alert'))
        <script>
            iziToast.warning({
                message: 'Anda disarankan untuk mengganti kata sandi!',
            });
        </script>
    @endif
@endpush