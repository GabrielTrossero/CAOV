<div class="form-inline container-fluid">
	<a href="{{ url('/') }}"><img src="{!! asset('images/logo2.png') !!}" width="80px"> </a>
	<h2> &nbsp; Club Atlético Oro Verde</h2>

			@auth
				<div class="btn-group ml-auto">
	 				<a target="_blank" href="{{asset('files/instructivo.pdf')}}" class="btn btn-primary-outline"><i class="icono2 fas fa-question-circle"></i></a>
					&nbsp;&nbsp;

					<form action="{{ url('/logout') }}" method="POST">
						@csrf
						<button type="submit" class="btn btn-light ml-auto">Cerrar sesión</button>
					</form>
				</div>
			@endauth


			&nbsp; &nbsp; &nbsp; &nbsp;				<!-- Para realizar espacios en blanco -->


</div>
