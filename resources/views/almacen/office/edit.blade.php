@extends ('layouts.admin')
@section ('contenido')
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<h3>Editar Oficina: {{ $office->name}}</h3>
			@if (count($errors)>0)
			<div class="alert alert-danger">
				<ul>
				@foreach ($errors->all() as $error)
					<li>{{$error}}</li>
				@endforeach
				</ul>
			</div>
			@endif

			{!!Form::model($office,['method'=>'PATCH','action'=>['OfficeController@update',$office->id]])!!}
                  {{Form::token()}}
                  <input type="hidden" name="id" value="{{ $office->id }}">
            <div class="form-group">
            	<label for="name">Nombre</label>
            	<input type="text" name="name" class="form-control" value="{{$office->name}}" placeholder="Nombre...">
            </div>
            <div class="form-group">
            	<label for="code">Código</label>
            	<input type="text" name="code" class="form-control" value="{{$office->code}}" placeholder="Código...">
            </div>

            <div class="form-group">
                  <label for="sigla">SIGLA</label>
                  <input type="text" name="sigla" class="form-control" value="{{$office->sigla}}" placeholder="SIGLA...">
            </div>

            <div class="form-group">
                  <label for="outstanding">Responsable/Jefe</label>
                  <select class="form-control" name="entity_id">
                        @foreach($entities as $entity)
                              @if($entity['id'] == $office['entity_id'])
                                    <option value="{{ $entity['id'] }}" selected="selected">{{ $entity['name'] }} {{ $entity['paternal_surname'] }}</option>
                              @else
                                    <option value="{{ $entity['id'] }}">{{ $entity['name'] }} {{ $entity['paternal_surname'] }}</option>
                              @endif
                        @endforeach
                  </select>
            </div>

            <div class="form-group">
                  <label for="outstanding">Oficina Superior</label>
                  <select class="form-control" name="upper_office_id">
                        <option value="0">No tiene</option>
                        @foreach($offices as $upper_office)
                              @if($upper_office['id'] == $office['upper_office_id'])
                              <option value="{{ $upper_office['id'] }}" selected="selected">{{ $upper_office['name'] }}</option>
                              @else
                              <option value="{{ $upper_office['id'] }}">{{ $upper_office['name'] }}</option>
                              @endif
                        @endforeach
                  </select>
            </div>

            <div class="form-group">
            	<button class="btn btn-primary" type="submit">Guardar</button>
                  <a href="/admin/oficinas" class="btn btn-danger">Cancelar</a>
            </div>

			{!!Form::close()!!}

		</div>
	</div>
@push ('scripts')
<script>
$('#liAlmacen').addClass("treeview active");
$('#liCategorias').addClass("active");
</script>
@endpush
@endsection