<div class="modal fade modal-slide-in-right" aria-hidden="true"
role="dialog" tabindex="-1" id="modal-entity-{{$cat->id}}">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
				aria-label="Close">
                     <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="modal-title">Remitente</h4>
			</div>
			<div class="modal-body">

				<div class="form-group">
					<label for="sigla">Nombre</label>
					<input type="text" class="form-control" value="{{ $cat->name }}" disabled="disabled">
				</div>

				<div class="form-group">
					<label for="sigla">Apellido Paterno</label>
					<input type="text" class="form-control" value="{{ $cat->paternal_surname }}" disabled="disabled">
				</div>

				<div class="form-group">
					<label for="sigla">Apellido Materno</label>
					<input type="text" class="form-control" value="{{ $cat->maternal_surname }}" disabled="disabled">
				</div>
				<div class="form-group">
					<label for="sigla">Documento de Identidad</label>
					<input type="text" class="form-control" value="{{ $cat->type_document == 1 ? 'DNI' : 'RUC' }} {{ $cat->identity_document }}" disabled="disabled">
				</div>
				<div class="form-group">
					<label for="sigla">Teléfono</label>
					<input type="text" class="form-control" value="{{ $cat->cellphone }}" disabled="disabled">
				</div>
				<div class="form-group">
					<label for="sigla">Correo</label>
					<input type="text" class="form-control" value="{{ $cat->email }}" disabled="disabled">
				</div>
				<div class="form-group">
					<label for="sigla">Dirección</label>
					<input type="text" class="form-control" value="{{ $cat->address }}" disabled="disabled">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>