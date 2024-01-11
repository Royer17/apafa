@extends ('layouts.admin')
@section ('contenido')
<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<h3>Mis Solicitudes Enviadas</h3>
		{!! Form::open(array('url'=>'admin/mis-solicitudes-enviadas','method'=>'GET','autocomplete'=>'off','role'=>'search')) !!}
		<div class="form-group">
			<div class="input-group">

			<div class="col-md-6" style="padding-left: 0px;padding-right: 0px">
				<input type="text" class="form-control" name="searchText" placeholder="Buscar por código" value="{{$searchText}}">
			</div>
			<div class="col-md-6" style="padding-left: 3px;padding-right: 0px">
				<select name="document_status" class="form-control">
					<option value="">TODOS</option>
					@foreach($document_statuses as $state)
						@if($state['id'] == $document_status)
							<option value="{{ $state['id'] }}" selected="selected">{{ $state['name'] }}</option>
						@else
							<option value="{{ $state['id'] }}">{{ $state['name'] }}</option>
						@endif
					@endforeach
				</select>
			</div>
			<span class="input-group-btn">
				<button type="submit" class="btn btn-primary">Buscar</button>
			</span>
			</div>
		</div>

		{{Form::close()}}


	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead>
					<th>#</th>
					<th>Código</th>
					<th>Tipo de documento</th>
					<th>Número</th>
					<th>Asunto</th>
					<th>De:</th>
					<th>Oficina donde está</th>
					<th>Estado</th>
					<th>Documento</th>
					<th>Opciones</th>
				</thead>
               @foreach ($orders as $key => $cat)
				<tr>
					<td>{{ $key+1}}</td>
					<td>{{ $cat->code}}</td>
					<td>{{ $cat->document_type_name}}</td>
					<td>{{ $cat->number}}</td>
					<td>{{ substr($cat->subject, 0, 50)}}...</td>
					<td> <a href="" data-toggle="modal" data-target="#modal-entity-{{$cat->id}}"> {{ $cat->name }} {{ $cat->paternal_surname }} {{ $cat->maternal_surname }}</a>
					</td>
					<td>{{ $cat->office_name}}</td>
					<td>{{ $cat->status_name}}</td>
					<td><a href="{{ $cat->attached_file}}" target="_blank">Ver Documento</a></td>
					<td>
						<form action="/admin/ruta-de-solicitud" method="POST" target="_blank">
							{{ csrf_field() }}
							<input type="hidden" name="solicitude_id" value="{{ $cat->id }}">
							<button type="submit" class="btn btn-success">Ver Ruta</button>
						</form>

					</td>
				</tr>
				@include('almacen.solicitude.entity')
				@endforeach
			</table>
		</div>
		{{$orders->render()}}
	</div>
</div>
@include('almacen.solicitude.modal')

@push ('scripts')
<script>
$('#liMySolicitudeSent').addClass("treeview active");

$(`#update-status-form`)[0].reset();

$(document).on('click', '.action', function(e){
	e.preventDefault();
	let _that = $(this).parent(), _order_id = _that[0].dataset.index, _new_status = _that[0].dataset.new_status;

	$(`.send-office`).hide();

	if (_new_status == 2) {
		$(`.send-office`).show();
	}

	axios.get(`/document-state/${_new_status}`)
		.then((response) => {
			$(`#order_id`).val(_order_id);
			$(`#status_id`).val(_new_status);

			$(`#modal-title`).html(`Se va a cambiar el estado a: ${response.data.name}`);

		});

});

document.querySelector(`#solicitude__update`)
	.addEventListener('click', () => {
		Swal.fire({
		  title: '¿Está seguro?',
		  text: "Va a cambiar el estado del documento",
		  icon: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Sí!',
		  cancelButtonText: 'No!'
		}).then((result) => {
			console.log(result);
		  if (result.value) {
		  	console.log("dawda");
		  	$(`#update-status-form`).submit();

		    // Swal.fire(
		    //   'Deleted!',
		    //   'Your file has been deleted.',
		    //   'success'
		    // )
		  }
		})
	});
</script>
@endpush
@endsection