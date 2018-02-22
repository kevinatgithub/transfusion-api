<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"> 
    <title>Blood Transfusion System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS are placed here -->
    {{ HTML::style('css/bootstrap.css') }}
    {{ HTML::style('css/bootstrap-theme.css') }}
    {{ HTML::style('css/styles.css') }}
    {{ HTML::style('css/styles.css') }}
    {{ HTML::style('js/lib/jquery_ui/css/smoothness/jquery-ui-1.9.2.custom.min.css')}}

    {{ HTML::script('js/jquery.v1.8.3.min.js') }}
    {{ HTML::script('js/lib/angular/angular.min.js') }}
    {{ HTML::script('js/lib/angular/angular-resource.min.js') }}
    {{ HTML::script('js/global.js') }}
    {{ HTML::script('js/lib/jquery_ui/js/jquery-ui-1.9.2.custom.min.js')}}
</head>
<body>
	<div class="container">
        @yield('content')
		@yield('content_script')
	</div>
	<!-- Scripts are placed here -->
    {{ HTML::script('js/bootstrap.min.js') }}
    {{ HTML::script('js/lib/bootbox/bootbox.min.js') }}
</body>
</html>