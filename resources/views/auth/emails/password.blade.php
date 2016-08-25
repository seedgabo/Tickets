{{-- resources/views/emails/password.blade.php --}}
 
Click  aqui para recuperar la contraseña: <a href="{{ url('password/reset/'.$token) }}">{{ url('password/reset/'.$token) }}</a>