<?php
	
	$items = ['0' => 'Apple' , '1' => 'Orange' , '2' => 'Mango'];
	EasyRow::$global_label_container = "<label class='control-label col-sm-1' >?</label>";
	Easy::$global_attr = ['class' => 'form-control'];
?>

@section('content')
	{{EasyRow::make('Test',[
		EasyText::make('test')
	])->render()}}


	{{EasyText::make('test')->render()}}

	{{EasyTextArea::make('test')->render()}}
	{{EasySelect::make('test',['items' => $items])->render()}}
	{{EasyRadioButton::make('test',['items' => $items])->render()}}

	{{EasyPassword::make('test')->render()}}
	{{EasyHidden::make('test')->render()}}
	{{EasyDate::make('test')->render()}}
	{{EasyCheckbox::make('test',['items' => $items])->render()}}
	{{EasyButton::make('test',[],'test')->render()}}
@stop