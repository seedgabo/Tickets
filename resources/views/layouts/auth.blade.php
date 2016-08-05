<!DOCTYPE html>
<html >

@include('layouts.partials.htmlheader')
	<body class="container-login">
		<center><img src="{{asset('img/logo.png')}}" alt="" height="100px"></center>
		<div class="login-card col-md-3">
			@yield('content')
		</div>
	</body>

</html>