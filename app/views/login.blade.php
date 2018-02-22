<?php
	$validation = isset($validation) ? $validation : null;
	EasyRow::$global_label_container = "<label class='control-label col-sm-3'>?</label>";
	EasyRow::$global_attr = ['class' => 'form-group'];
	Easy::$global_attr = ['class' => 'form-control  has-tooltip','parent_class' => 'col-sm-9'];
	EasyRow::$validation = isset($validation) ? $validation : null;
	
?>
@section('content')
	<div class="row" style="margin-top:2em;">
		<div class="col-lg-4 col-md-2"></div>
		<div class="col-lg-4 col-md-8 col-sm-12">
			<fieldset>
				<legend>User Login</legend>
				@if($general_failure != null)
					<div class="col-sm-12">
						<b class="text-danger pull-right">
							{{$general_failure}}
						</b>
					</div>
					<br/><br/>
				@endif
				{{Form::open(['class'=>'form-horizontal'])}}
					{{EasyRow::make('User ID',[
						EasyText::make('user_id',['placeholder' => 'User ID'],'User ID')
					])->render()}}
					{{EasyRow::make('Password',[
						EasyPassword::make('password',['placeholder' => 'Password'],'Password')
					])->render()}}
					{{Form::submit('Login',['class' => 'btn btn-success pull-right'])}}
				{{Form::close()}}
			</fieldset>
		</div>
		<div class="col-lg-4 col-md-2"></div>
	</div>
@stop