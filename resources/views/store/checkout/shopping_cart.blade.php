@extends('store.layouts.index')
@section('content')
<body style="">
	<div id="header" class="fixed-top">
	</div>

	@foreach($orders as $order)
    <div id="result_ct" class="container mt-1 small" style="margin-bottom: 50px;">
		<div id="20190011135391" class="card mt-2">
		  <div class="card-header h6" style="text-transform:uppercase">
		   Código de Registro :  {{ $order['code'] }} 

		  </div>
		  <div class="card-body">
		  	<div class="text-mutted">{{ $company['name'] }}</div>

			<div class="text-mutted"><small>            </small></div>
			<blockquote class="blockquote mb-1">
				<div class="card-text"> {{ $order['entity']['name'] }} {{ $order['entity']['paternal_surname'] }} {{ $order['entity']['maternal_surname'] }} </div>
			  <div class="card-text "> {{ $order['document_type']['name'] }} {{ $order['number'] }}</div>
			  
			  <div class="card-text small" style="">{{ $order['office']['name'] }}</div>
			  
			  <div class="card-text font-italic" style="margin-top: -7px; font-size: 65%;">JEFE DE OFICINA </div>
			  <footer class="blockquote-footer" style="margin-top: -5px; "><small></small></footer>
			</blockquote>

		    <div class="row">
		    	<div class="text-muted col-2">{{ $order['created_at']->format('d/m/Y') }}</div>
		    	<div class="text-muted col-2">{{ $order['folios'] }}&nbsp;folio(s)</div>
		    </div>
			<div class="mb-1" style="padding: 3px 5px 3px 10px; position: relative;">
				<span class="" style="font-family: 'Georgia', 'Apple Symbols', serif; font-size: 1.5em; position: absolute; left: 0px; top: 0px;">“</span>
				<div class="d-inline-block" style="padding-right: 10px; position: relative;">
					{{ $order['subject'] }}
					<span class="" style="font-family: 'Georgia', 'Apple Symbols', serif; font-size: 1.5em; position: absolute; right: 0px; top: 0px;">”</span>
				</div>
			</div>

			<div class="card-text text-muted" style="border: 1px #ddd dashed; padding: 3px 7px;">{{ $order['notes'] }}</div>

		  	<div class="">
		  	</div>
		  </div>
		  <table class="table">
			<thead>
			<tr>
				<th scope="col" style="width: 50px;">#</th>
				<th scope="col" style="width: 175px;">Transacción / Fecha</th>
				<th scope="col">Dependencia Origen</th>
				<th scope="col">Dependencia Destino</th>
				<th scope="col">Fecha Emisión</th>
				<th scope="col">Fecha Recepción</th>
				<th scope="col">Proveido / Observacion</th>
			</tr>
			</thead>
			<tbody>
				@php
					$detail = $order['detail'];
				@endphp
				<tr id="tr_2468827" class="">
					<th id="th_2468827" scope="row">#</th>
					<td>
						<div class="" style="">{{ $detail['state']['name'] }}&nbsp;
					<!-- 	<button type="button" class="anterior-bt btn btn-outline-info btn-sm float-right" style="font-size: .7rem; padding: .15rem;" data-toggle="button" aria-pressed="false" title="Transaccion anterior" onclick="go_to_transac_row(2457163)">
							1
						</button> --></div>
						<span class="text-muted">{{ $detail['created_at']->format('d/m/Y H:i') }}</span>

					</td>
					<td>
						<div>Calana </div>
						<div>Sistema Web</div>
					</td>
					<td>
						
						<div>{{ $detail['office']['name'] }} </div>
						<div>Jefe de Oficina</div>
					</td>
					<td>{{ $detail['created_at'] }}</td>
					<td>--</td>

					<td>{{ $detail['observations'] }}</td>
				</tr>
		</tbody>
	  </table>
	</div>
	</div>
	@endforeach
</body>
@stop
