@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Buat Akun / {{ ($role === 'admin') ? ucfirst($role) : 'Pengguna' }}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{ route('admin-accounts-' . $role) }}">Data Akun {{ ($role === 'admin') ? ucfirst($role) : 'Pengguna' }}</a></div>
            <div class="breadcrumb-item">Buat Akun</div>
        </div>
    </div>
    <div class="section-body">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-6">
                    <div class="card p-3">
                        <div class="card-body pb-0">
                            <form action="{{ route('admin-accounts-store', ['role' => $role]) }}" method="post" id="form-create">
                                @csrf
                                <div class="form-group">
                                    <label>Nama</label>
                                    <input type="text" class="form-control" id="name" name="name">
                                    <div class="invalid-feedback" id="name_error"></div>
                                </div>
                                <div class="form-group">
                                    <label>Nomor Unik</label>
                                    <input type="text" class="form-control" id="unique_code" name="unique_code">
                                    <div class="invalid-feedback" id="unique_code_error"></div>
                                </div>
                                <div class="form-group">
                                    <label>Nama Pengguna</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="username" name="username" readonly>
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button" id="copy" title="Tersalin"><i class="fas fa-copy"></i></button>
                                        </div>
                                        <div class="invalid-feedback" id="username_error"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Kata Sandi</label>
                                    <input type="text" class="form-control" id="password" name="password" readonly>
                                    <div class="invalid-feedback" id="password_error"></div>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer">
                            <button type="reset" class="btn btn-outline-danger" id="btn-reset"><i class="fas fa-redo-alt mr-2"></i></i>Atur Ulang</button>
                            <button type="submit" class="btn btn-success float-right" form="form-create"><i class="fas fa-plus mr-2"></i>Buat</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('js-libraries')
    <script src="{{ asset('node_modules/sweetalert/dist/sweetalert.min.js') }}"></script>
@endpush

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#name').on('focusout', function (e) {
                const name = $('#name').val().toLowerCase().trim();
                const first_name = name.split(/(\s+)/, 1);
                $('#username').val(first_name + '-');
                $('#password').val(first_name + '-');
            });

            $('#unique_code').on('keypress', function (e) {
                const code = String.fromCharCode(e.which);
                let username = $('#username').val();
                username += code;
                $('#username').val(username);
                $('#password').val(username);
            });

            $('#copy').tooltip('disable');
            $('#copy').on('click', function () {
                $('#copy').tooltip('enable');
                $('#username').select();
                document.execCommand('copy');
                $('#copy').tooltip('show');
                setTimeout(() => {
                    $('#copy').tooltip('dispose');
                }, 1000);
            });
        });
    </script>
@endpush