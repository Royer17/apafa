@extends('store.layouts.index')
@section('content')

    <!-- Product Section Begin -->
    <section class="product spad header-shadow">
        <div class="container">

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div id="form_container" class="container">
                        <div class="card-header">Se ha procesado su solicitud. Su Código Solicitud es: {{ $order->code }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Product Section End -->
@stop

@section('plugins-js')


@stop