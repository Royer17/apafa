@extends ('layouts.admin')
@section ('contenido')
<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<h3>Listado de Oficinas <a href="/admin/oficinas/create"><button class="btn btn-success">Nuevo</button></a></h3>
		@include('almacen.office.search')
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead>
					<th>Id</th>
					<th>Código</th>
					<th>Nombre</th>
					<th>SIGLA</th>
					<th>Opciones</th>
				</thead>
               @foreach ($offices as $key => $cat)
				<tr>
					<td>{{ $key+1}}</td>
					<td>{{ $cat->code}}</td>
					<td>{{ $cat->name}}</td>
					<td>{{ $cat->sigla}}</td>
					<td>
						<a href="{{URL::action('OfficeController@edit',$cat->id)}}"><button class="btn btn-info">Editar</button></a>
                         <a href="" data-target="#modal-delete-{{$cat->id}}" data-toggle="modal"><button class="btn btn-danger">Eliminar</button></a>
					</td>
				</tr>
				@include('almacen.office.modal')
				@endforeach
			</table>
		</div>
		{{$offices->render()}}
	</div>
</div>
@push ('scripts')
<script>
$('#liAcceso').addClass("treeview active");
$('#liOficinas').addClass("active");
</script>
@endpush
@endsection