<!DOCTYPE html>
<html >

@include('layouts.partials.htmlheader')
	<body class="container-login">
		<center><img src="{{asset('img/logo.png')}}" alt="" height="100px"></center>
		<br><br>
		<center>
			<div class="login-card text-center">
				@yield('content')
			</div>
		</center>
	</body>

</html>