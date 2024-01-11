@extends ('layouts.admin')
@section ('contenido')
<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<h3>Listado del Personal <a href="/admin/personal/create"><button class="btn btn-success">Nuevo</button></a></h3>
		@include('almacen.entity.search')
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead>
					<th>Id</th>
					<th>Documento de identidad</th>
					<th>Nombres</th>
					<th>Celular</th>
					<th>Profesión</th>
					<th>Opciones</th>
				</thead>
               @foreach ($entities as $key => $cat)
				<tr>
					<td>{{ $key+1}}</td>
					<td>{{ $cat->identity_document}}</td>
					<td>{{ $cat->full_name}}</td>
					<td>{{ $cat->cellphone}}</td>
					<td>{{ ($cat->profession_name) ? $cat->profession_name : 'No elegida'}}</td>
					<td>
						<a href="{{URL::action('EntityController@edit',$cat->id)}}"><button class="btn btn-info">Editar</button></a>
                         <a href="" data-target="#modal-delete-{{$cat->id}}" data-toggle="modal"><button class="btn btn-danger">Eliminar</button></a>
					</td>
				</tr>
				@include('almacen.entity.modal')
				@endforeach
			</table>
		</div>
		{{$entities->render()}}
	</div>
</div>
@push ('scripts')
<script>
$('#liAcceso').addClass("treeview active");
$('#liPersonal').addClass("active");
</script>
@endpush
@endsection