{!! Form::open(array('url'=>'admin/solicitudes','method'=>'GET','autocomplete'=>'off','role'=>'search')) !!}
<div class="form-group">
	<div class="input-group">
		<div class="col-md-6" style="padding-left: 0px;padding-right: 0px">
			<input type="text" class="form-control" name="searchText" placeholder="Buscar por código, DNI o RUC..." value="{{$searchText}}">
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