@extends('layouts.app')

@push('css-libraries')
    <link rel="stylesheet" href="{{ asset('node_modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('node_modules/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}">
@endpush

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Data Akun Admin</h1>
    </div>
    <div class="section-body">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <a href="{{ route('admin-accounts-create', ['role' => 'admin']) }}" class="btn btn-primary"><i class="fas fa-plus mr-2"></i>Buat Akun</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered text-center" id="data-table">
                                    <thead>
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Nama</th>
                                            <th scope="col">Nama Pengguna</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($admins as $no => $admin)
                                            <tr>
                                                <th scope="row" class="align-middle">{{ ++$no }}</th>
                                                <td class="align-middle"><h6 class="mb-0">{{ $admin->name }}</h6></td>
                                                <td class="align-middle">{{ $admin->username }}</td>
                                                <td class="align-middle">
                                                    @if ($admin->is_active === 1)
                                                        <span class="badge badge-success">
                                                            Aktif
                                                        </span>
                                                    @else
                                                        <span class="badge badge-danger">
                                                            Non-aktif
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="align-middle">
                                                    @if (session()->get('user_id') === $admin->id)
                                                        <span class="badge badge-primary">Anda</span>
                                                    @else
                                                        <form action="{{ route('admin-accounts-update') }}" method="post" class="form-status">
                                                            @method('put')
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{ $admin->id }}">
                                                            @if ($admin->is_active === 1)
                                                                <button type="submit" class="btn btn-primary"><i class="fas fa-lock mr-2"></i>Non-aktifkan</button>
                                                            @else
                                                                <button type="submit" class="btn btn-primary"><i class="fas fa-lock-open mr-2"></i>Aktifkan</button>
                                                            @endif
                                                        </form>
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