<!-- 
`                    ````````                    `
                `.-://////////:-.`                
              .://///:.....-://///:.              
            -//////-`     `   .//////-            
          `///////`   `...:`    -//////`          
         .///////.    ` -:`      ///////.         
        `////////`-:-. ```` `.-:.:///////`        
        :////////:-////-  `:////.////////:        
        //////////..////-`/////.//////////        
        ///////////:`...-`:-.`.///////////        
        :////////////`       :///////////:        
        .//////...-:. `    ` .:....://///.        
         -////- .`..    ``    .-`- `////-         
          -////` .`.-:///////..`-` :///-          
           `:///. `.-////////:.. `://:`           
             .://///////////////://:.             
               `.-//////////////:.                
                    `........`                    
                          `.`                     
                   .    `hNdh                     
   `ymhymh:   /mmmmh    hNm.    omh`  .mm: +my`   
    sNNd:    -yNd++o   sNmNd`   /Ny    mNmo/Ns    
    sNmms.   :dNdooo  /NN-yNy   /Ny    mm+dNNs    
   `yNs-yms.  /Nmddh -mN+ `hNs  oNh   `Nm` sNy    
    ...  `..  :o...- `..    ..  `..`  `..` `..    
                                                  
                                                  
    .::.      -:-.     ....`      ......    .-.   
  -dNhydm   +mmmmmd:   /Nmydd/    yNmhh+  `dNsy/  
  mN.   :  :NmomdomN.  :Nd  yN/  odNdsy+   yNh+`  
  yN+``-y  .mm/dhoNm`  :Nd`:dN:  /hNy/+/  `/.oNm  
   /ydmds   `+hmmho`   +dddds-    yNddd+  `sdmd+  
                                  :.   `          
`                                     ````````````
 -->
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
    {{ HTML::style('js/lib/jquery_ui/css/smoothness/jquery-ui-1.9.2.custom.min.css')}}

    {{ HTML::script('js/jquery.v1.8.3.min.js') }}
    {{ HTML::script('js/lib/angular/angular.min.js') }}
    {{ HTML::script('js/lib/angular/angular-resource.min.js') }}
    {{ HTML::script('js/lib/angular/angular-route.min.js') }}
    {{ HTML::script('js/global.js') }}
    {{ HTML::script('js/lib/jquery_ui/js/jquery-ui-1.9.2.custom.min.js')}}
    {{ HTML::script('js/jquery.mask.js')}}
    {{ HTML::script('js/jQuery.print.js')}}

    <link rel="shortcut icon" href="{{URL::to('logo.png')}}">
</head>
<body>
	<div class="navbar navbar-inverse" role="navigation" id="header">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
	                <span class="sr-only">Toggle navigation</span>
	                <span class="icon-bar"></span>
	                <span class="icon-bar"></span>
	                <span class="icon-bar"></span>
	            </button>
				<a class="navbar-brand" href="{{URL::to('/')}}" style="position:absolute;">
					<img  src="{{URL::to('header.png')}}" class="img-responsive" />
				</a>
			</div>
			<div class="navbar-collapse collapse">
			 	@yield('nav')
             
            </div><!--/.nav-collapse -->
		</div>
	</div>
	<div class="container-fluid" style="padding-bottom:40px;">
		@yield('content')
	</div>
	<div class="navbar navbar-fixed-bottom navbar-default">
        <div class="container-fluid">
        	<div class="navbar-text text-deleted">
            	National Voluntary Blood Services Program 
            	@if(User::current() != null)
					<?php $current_user = User::current() ?>
					<div class="pull-right" style="margin-left:5em;">
						Current User : 
						<span class="text-info">
							{{$current_user->user_fname.' '.$current_user->user_mname.' '.$current_user->user_lname}} 
							({{$current_user->user_id}})
						</span>
					</div>
					
				@endif
            </div>
        </div>
	</div>
	<!-- Scripts are placed here -->
    {{ HTML::script('js/bootstrap.min.js') }}
    {{ HTML::script('js/lib/bootbox/bootbox.min.js') }}
    {{ HTML::script('js/lib/jquery.datetimepicker/jquery.datetimepicker.js') }}
    {{ HTML::style('js/lib/jquery.datetimepicker/jquery.datetimepicker.css') }}
</body>
</html>