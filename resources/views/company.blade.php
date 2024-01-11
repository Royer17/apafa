@extends ('layouts.admin')
@section ('contenido')
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <h3>Editar Datos de la Minicipalidad</h3>
            @if (count($errors)>0)
            <div class="alert alert-danger">
                <ul>
                @foreach ($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
                </ul>
            </div>
            @endif

            {!!Form::model($company,['method'=>'POST','route'=>['company.update'], 'files'=>'true'])!!}
            {{Form::token()}}

            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" name="name" class="form-control" value="{{$company->name}}" placeholder="Nombre">
            </div>

            <div class="form-group">
                <label for="nombre">Logo</label>
                <input type="file" name="logo" class="form-control" value="" placeholder="LOGOTYPE">
                <br>
                @if (($company->logo)!="")
                    <img src="{{asset($company->logo)}}">
                @endif
            </div>

            <div class="form-group">
                <label for="nombre">Descripción</label>
                <textarea name="description" class="form-control" placeholder="Acerca de nosotros">{{$company->description}}</textarea>
            </div>

            <div class="form-group">
                <label for="nombre">Video</label>
                <input type="text" name="video_url" class="form-control" value="{{$company->video_url}}" placeholder="URL del video">
            </div>

            {{-- <div class="form-group">
                <label for="nombre">Logo</label>
                <input type="text" name="logo" class="form-control" value="{{$company->logo}}" placeholder="Logo">
            </div> --}}

            <div class="form-group">
                <label for="nombre">Dirección</label>
                <input type="text" name="address" class="form-control" value="{{$company->address}}" placeholder="Dirección">
            </div>

            <div class="form-group">
                <label for="nombre">Email</label>
                <input type="text" name="email" class="form-control" value="{{$company->email}}" placeholder="Email">
            </div>

            <div class="form-group">
                <label for="nombre">Teléfono 1</label>
                <input type="text" name="phone_1" class="form-control" value="{{$company->phone_1}}" placeholder="Teléfono 1">
            </div>

            <div class="form-group">
                <label for="nombre">Teléfono 2</label>
                <input type="text" name="phone_2" class="form-control" value="{{$company->phone_2}}" placeholder="Teléfono 2">
            </div>

            <div class="form-group">
                <label for="nombre">Celular 1</label>
                <input type="text" name="cellphone_1" class="form-control" value="{{$company->cellphone_1}}" placeholder="Celular 1">
            </div>

            <div class="form-group">
                <label for="nombre">Celular 2</label>
                <input type="text" name="cellphone_2" class="form-control" value="{{$company->cellphone_2}}" placeholder="Celular 2">
            </div>

            <div class="form-group">
                <label for="nombre">Whatsapp</label>
                <input type="text" name="whatsapp" class="form-control" value="{{$company->whatsapp}}" placeholder="Whatsapp">
            </div>
            {{--
            <div class="form-group">
                <label for="nombre">YAPE QR</label>
                <input type="file" name="yape_qr" class="form-control" value="" placeholder="YAPE QR">
                <br>
                @if (($company->yape_qr)!="")
                    <img src="{{asset($company->yape_qr)}}" height="300px" width="300px">
                @endif
            </div>
            --}}
            <div class="form-group">
                <label for="nombre">Imágen(870x430)</label>
                <input type="file" name="image" class="form-control" value="" placeholder="Imágen">
                <br>
                @if (($company->image)!="")
                    <img src="{{asset($company->image)}}" width="100%">
                @endif
            </div>

            <div class="form-group">
                <button class="btn btn-primary" type="submit" title="Guardar">Guardar</button>
                <button class="btn btn-danger" type="reset" title="Remover todo lo escrito">Cancelar</button>
            </div>

            {!!Form::close()!!}

        </div>
    </div>
@push ('scripts')
<script>
    $('#liAcceso').addClass("treeview active");
    $('#liMunicipalidad').addClass("active");
</script>
@endpush
@endsection