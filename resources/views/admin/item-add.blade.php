@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Tambah Barang</h1>
    </div>
    <div class="section-body">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-import"><i class="fas fa-upload mr-2"></i>Unggah Massal (Excel)</button>
                            <a href="{{ route('admin-download-template') }}" class="btn btn-primary ml-auto"><i class="fas fa-download mr-2"></i>Unduh Templat Excel</a>
                        </div>
                        <div class="card-body px-5">
                            <form action="{{ route('admin-item-store') }}" method="post" id="form-add-item">
                                @csrf
                                <div class="form-group">
                                    <label for="admin_id">ID Admin</label>
                                    <input type="text" class="form-control" id="admin_id" name="admin_id" value="{{ session()->get('user_id') }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="name">Nama Barang</label>
                                    <input type="text" class="form-control" id="name" name="name">
                                    <div class="invalid-feedback" id="name_error"></div>
                                </div>
                                <div class="form-group">
                                    <label for="category">Kategori</label>
                                    <select class="form-control" id="category" name="category">
                                        <option value="">-</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="category_error"></div>
                                </div>
                                <div class="form-group">
                                    <label for="stock">Stok</label>
                                    <input type="number" class="form-control" id="stock" name="stock" min="0">
                                    <div class="invalid-feedback" id="stock_error"></div>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success" form="form-add-item"><i class="fas fa-plus mr-2"></i>Tambah</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="modal fade" tabindex="-1" role="dialog" id="modal-import" aria-modal="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Unggah Massal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                Pilih berkas templat (.xlsx) yang sudah diisi data.
                <form action="{{ route('admin-import') }}" method="post" id="form-import" enctype="multipart/form-data">
                    @csrf
                    <div class="custom-file mb-2 mt-3">
                        <input type="file" class="custom-file-input" id="fileimport" name="fileimport">
                        <label class="custom-file-label" for="fileimport" data-browse="Pilih">Berkas</label>
                        <div class="invalid-feedback mt-3 mb-0 ml-2" id="fileimport_error"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-whitesmoke">
                <button type="submit" class="btn btn-success" form="form-import"><i class="fas fa-upload mr-2"></i>Unggah</button>
            </div>
        </div>
    </div>
</div>
@if (session('failed'))
    <div id="flash-data" data-flashdata="{{ session('failed') }}"></div>
@endif
@endsection

@push('js-libraries')
    <script src="{{ asset('node_modules/sweetalert/dist/sweetalert.min.js') }}"></script>
@endpush

@push('scripts')
    <script>
        $(document).ready(function () {
            $('.custom-file-input').on('change', function () {
                let fileName = document.getElementById("fileimport").files[0].name;
                $('.custom-file-label').text(fileName);
            });

            const flashdata = $('#flash-data').data('flashdata');
            if (flashdata) {
                swal({
                    title: "Kesalahan",
                    text: flashdata,
                    icon: "error",
                    timer: 2500,
                    buttons: false
                });
            }
        });
    </script>
@endpush