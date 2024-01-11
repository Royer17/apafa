@extends ('layouts.admin')
@section ('contenido')
<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<h3>Listado de Usuarios <a href="usuario/create"><button class="btn btn-success">Nuevo</button></a></h3>
		@include('seguridad.usuario.search')
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead>
					<th>Id</th>
					<th>Nombre</th>
					<th>Username</th>
					<th>Opciones</th>
				</thead>
               @foreach ($usuarios as $usu)
				<tr>
					<td>{{ $usu->user_id}}</td>
					<td>{{ $usu->entity_name}} {{ $usu->entity_paternal_surname }} {{ $usu->entity_maternal_surname }}</td>
					<td>{{ $usu->user_name}}</td>
					<td>
						<a href="{{URL::action('UsuarioController@edit',$usu->user_id)}}"><button class="btn btn-info">Editar</button></a>
                         <a href="" data-target="#modal-delete-{{$usu->user_id}}" data-toggle="modal"><button class="btn btn-danger">Eliminar</button></a>
					</td>
				</tr>
				@include('seguridad.usuario.modal')
				@endforeach
			</table>
		</div>
		{{$usuarios->render()}}
	</div>
</div>
@push ('scripts')
<script>
$('#liAcceso').addClass("treeview active");
$('#liUsuarios').addClass("active");
</script>
@endpush
@endsection