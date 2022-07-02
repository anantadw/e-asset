@extends('layouts.app')

@section('auth')
<section class="section">
	<div class="container mt-4">
		<div class="row">
			<div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
				<div class="login-brand">
					<img src="{{ asset('img/stisla-fill.svg') }}" alt="logo" width="100" class="shadow-light rounded-circle">
				</div>
				@if (session()->has('flash-fail'))
					<div class="alert alert-danger alert-dismissible show fade">
						<div class="alert-body">
							<button class="close" data-dismiss="alert">
								<span>×</span>
							</button>
							{{ session('flash-fail') }}
						</div>
					</div>
				@endif
				<div class="card card-primary">
					<div class="card-header"><h4>Masuk</h4></div>
					<div class="card-body">
						<form method="post" action="{{ route('login') }}">
							@csrf
							<div class="form-group">
								<label for="username">Nama Pengguna</label>
								<input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" autofocus>
								@error('username')
									<div class="invalid-feedback">
										{{ $message }}
									</div>
								@enderror
							</div>
							<div class="form-group">
								{{-- <div class="d-block">
									<div class="float-right">
										<a href="" class="text-small">
											Lupa Kata Sandi?
										</a>
									</div>
								</div> --}}
								<label for="password" class="control-label">Kata Sandi</label>
								<input id="password" type="password" class="form-control  @error('password') is-invalid @enderror" name="password">
								@error('password')
									<div class="invalid-feedback">
										{{ $message }}
									</div>
								@enderror
							</div>
							<div class="form-group">
								<button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">Masuk</button>
							</div>
						</form>
					</div>
				</div>
				<div class="simple-footer">
					Hak Cipta &copy; 2021 E-Asset Jurusan RPL oleh Stisla
				</div>
			</div>
		</div>
	</div>
</section>
@endsection