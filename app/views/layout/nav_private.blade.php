<?php 
    $user = User::current();
    $currentPage = isset($currentPage) ? $currentPage : null; ?>
@section('nav')
	 <ul class="nav navbar-nav navbar-right">
	 	<li class="dropdown {{$currentPage == 'Blood Request' ? 'active' : null}}">
	 		<a class="dropdown-toggle" data-toggle="dropdown" href="#">Blood Request <b class="caret"></b></a>
	 		<ul class="dropdown-menu">
	 			<li>{{HTML::link('BloodRequest','Manage Requests')}}</li>
	 			<li>{{HTML::link('BloodRequest/create','Create New Requests')}}</li>
	 		</ul>
	 	</li>
      	<li class="dropdown {{$currentPage == 'Patient' ? 'active' : null}}">
        	<a class="dropdown-toggle" data-toggle="dropdown" href="#">Patient <b class="caret"></b></a>
        	<ul class="dropdown-menu">
        		<li>{{HTML::link('Patient','Manage Patients')}}</li>
        		<li>{{HTML::link('Patient/create','Create new Patient')}}</li>
        	</ul>
        </li>
        <li class="dropdown {{$currentPage == 'Physician' ? 'active' : null}}">
        	<a class="dropdown-toggle" data-toggle="dropdown" href="#">Physician <b class="caret"></b></a>
        	<ul class="dropdown-menu">
        		<li>{{HTML::link('Physician','Manage Physician')}}</li>
                <li>{{HTML::link('Physician/create','Create new Physician')}}</li>
        	</ul>
        </li>
        <li class="dropdown {{$currentPage == 'Reports' ? 'active' : null}}">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">Reports <b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li>{{HTML::link('Census','Census Summary')}}</li>
            </ul>
        </li>
        @if($user->ulevel == '-1')
        <li class="dropdown {{$currentPage == 'Reference' ? 'active' : null}}">
        	<a class="dropdown-toggle" data-toggle="dropdown" href="#">Reference <b class="caret"></b></a>
        </li>
        @endif
        <li>{{HTML::link('logout','Logout')}}</li>
      </ul>
@stop