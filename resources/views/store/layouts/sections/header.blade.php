
    <header style="height: auto ">
        <div class="container">
        <div class="row">
            <nav class="navbar navbar-expand-lg col col-lg-12 navbar-light bg-light">
             <a href="#" class="col col-lg-10"><img width="300" height="80" class="img-fluid  "  src="{{ $company->logo }}" alt=""></a>
               <form class="form-inline col col-lg-2 ">
                    <button
                            class="navbar-toggler btn btn-outline-success my-2 my-sm-0"
                            type="button"
                            data-toggle="collapse"
                            data-target="#navbarNavDropdown"
                            aria-controls="navbarNavDropdown"
                            aria-expanded="false"
                            aria-label="Toggle navigation"
                        >
                    <span class="navbar-toggler-icon"></span>
                    </button>
                    @if($search_button)
                    <div class="collapse navbar-collapse  rounded float-right" id="navbarNavDropdown">
                        <ul class="navbar-nav">
                            <li class="nav-item active">
                                <a class="form-control btn btn-warning nav-link" href="/busqueda-documento">Consultar Tramite</a>
                            </li>
                        </ul>
                   </div>
                   @endif
               </form>
            </nav>
        
        </div>


        </div>
    </header>
    <!-- Header Section Begin -->
 <!--

    <header class="header">
        {{-- <div class="header__top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <div class="header__top__left">
                            <ul>
                                <li><i class="fa fa-mobile"></i> {{ $company->whatsapp }}</li>
                                <li>{{ $company->name }} </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="header__top__right">
                            <div class="header__top__right__social">
                                <a href="#"><i class="fa fa-facebook"></i></a>
                                <a href="#"><i class="fa fa-twitter"></i></a>
                                <a href="#"><i class="fa fa-linkedin"></i></a>
                                <a href="#"><i class="fa fa-pinterest-p"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="header__logo" style="text-align: center;">
                        <a href="/"><img  width="100" height="150"  src="{{ $company->logo }}" alt=""></a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <nav class="header__menu">
                        <ul>
                            <li class="active"><a href="/">Inicio</a></li>
                            <li><a href="/busqueda-documento">Consultar Trámite</a></li>


                        </ul>
                    </nav>
                </div>
                <div class="col-lg-3">
                    <div class="header__cart">


                    </div>
                </div>
            </div>
            <div class="humberger__open">
                <i class="fa fa-bars"></i>
            </div>
        </div>
    </header>
    Header Section End -->

