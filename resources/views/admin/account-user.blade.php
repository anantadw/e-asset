@extends('layouts.app')

@push('css-libraries')
    <link rel="stylesheet" href="{{ asset('node_modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('node_modules/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}">
@endpush

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Data Akun Pengguna</h1>
    </div>
    <div class="section-body">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <a href="{{ route('admin-accounts-create', ['role' => 'user']) }}" class="btn btn-primary"><i class="fas fa-plus mr-2"></i>Buat Akun</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered text-center" id="data-table">
                                    <thead>
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Nama</th>
                                            <th scope="col">Nama Pengguna</th>
                                            <th scope="col">Nomor Unik</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $no => $user)
                                            <tr>
                                                <th scope="row" class="align-middle">{{ ++$no }}</th>
                                                <td class="align-middle"><h6 class="mb-0">{{ $user->name }}</h6></td>
                                                <td class="align-middle">{{ $user->username }}</td>
                                                <td class="align-middle">{{ $user->unique_code }}</td>
                                                <td class="align-middle">
                                                    @if ($user->is_admin === 0)
                                                        Siswa
                                                    @endif
                                                </td>
                                                <td class="align-middle"><button type="button" class="btn btn-danger btn-delete" data-toggle="modal" data-target="#modal-delete" data-id="{{ $user->id }}" data-name="{{ $user->name }}"><i class="fas fa-trash mr-2"></i>Hapus</button></td>
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
                <h5 class="modal-title">Hapus Pengguna</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                Anda yakin ingin menghapus <span class="font-weight-bold" id="name-delete"></span>?<br>
            </div>
            <div class="modal-footer bg-whitesmoke">
                <form action="{{ route('admin-accounts-delete') }}" method="post" id="form-delete">
                    @method('delete')
                    @csrf
                    <input type="hidden" name="id" id="id" value="">
                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash mr-2"></i>Hapus</button>
                </form>
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
        });
    </script>
@endpush