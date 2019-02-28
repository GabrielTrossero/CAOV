<div class="form-inline container-fluid">
	<a href="{{ url('/') }}"><img src="{!! asset('images/logo2.png') !!}" width="80px"> </a>
	<h2> &nbsp; Club Atlético Oro Verde</h2>

    <!AGREGAR QUE NO SE MUESTRE EN EL LOGIN>
			<div class="btn-group ml-auto">    <!-- Boton del header -->
				<button type="button" class="btn btn-light dropdown-toggle"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Opciones
				</button>
				  <div class="dropdown-menu">
				    <a class="dropdown-item" href="{{ url('/administrador') }}">Mis opciones</a>
				    <a class="dropdown-item" href="#">Mi Perfil</a>
			     <div class="dropdown-divider"></div>
				     <a class="dropdown-item" href="#">Salir</a>
				   </div>
			</div>

			&nbsp; &nbsp; &nbsp; &nbsp;				<!-- Para realizar espacios en blanco -->


</div>
