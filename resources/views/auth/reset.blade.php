@extends('layouts.layout')

@section('content')
<div class="container-fluid">
	<div class="row paneles">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-login">
				<div class="panel-heading">Cambiar Contraseña</div>
				<div class="panel-body">
					@include('partials.mensajes')
					<form class="form-horizontal" role="form" method="POST" action="{{ url('/password/reset') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						

						<div class="form-group">
							<label class="col-md-4 control-label">Correo Electrónico</label>
							<div class="col-md-6">
								<input type="email" class="form-control" name="email" value="{{ old('email') }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Contraseña</label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="password">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Confirmar Contraseña</label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="password_confirmation">
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">
									Restablecer Contraseña
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
