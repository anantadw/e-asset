@extends('layouts.app')

@push('css-libraries')
    <link rel="stylesheet" href="{{ asset('node_modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('node_modules/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}">
@endpush

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Detail Barang / {{ $item->name }}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{ route('admin-index') }}">Dasbor</a></div>
            <div class="breadcrumb-item">Detail Barang</div>
        </div>
    </div>
    <div class="section-body">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    @if ($broken_items->isNotEmpty())
                        <div class="alert alert-danger alert-has-icon">
                            <div class="alert-icon"><i class="fas fa-exclamation-triangle"></i></div>
                            <div class="alert-body">
                                <div class="alert-title mb-0 ml-2">Perhatian: {{ $broken_items->count() }} barang rusak.</div>
                            </div>
                        </div>
                    @endif
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered text-center" id="data-table">
                                    <thead>
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Nama dan Kode Barang</th>
                                            <th scope="col">Kode Unik</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($item_details as $no => $item_detail)
                                            <tr>
                                                <th scope="row" class="align-middle">{{ ++$no }}</th>
                                                <td class="align-middle">{{ $item_detail->codename }}</td>
                                                <td class="align-middle">{{ $item_detail->unique_code }}</td>
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
                                                    <form action="{{ route('admin-item-detail-status', ['slug' => $item->slug]) }}" method="post" class="form-status">
                                                        @method('put')
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $item_detail->id }}">
                                                        <button type="submit" class="btn @if ($item_detail->status === '1') btn-primary @elseif ($item_detail->status === '2') btn-primary disabled @else btn-danger @endif"><i class="fas fa-sync-alt mr-2"></i>Ubah Status</button>
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
        });
    </script>
@endpush