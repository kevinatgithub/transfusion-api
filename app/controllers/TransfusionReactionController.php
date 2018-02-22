<?php
	class TransfusionReactionController extends BaseController{

		public $restful = true;
		public $layout = 'layout.default';

		function setNav($currentPage = null){
			$currentPage = 'References';
			if(User::guest()){
				$this->layout->nav = View::make('layout.nav_public');
			}else{
				$this->layout->nav = View::make('layout.nav_private',['currentPage' => $currentPage]);
			}
		}

		function get_index(){
			$this->setNav();
			$this->layout->content = View::make('transfusionReaction.list');
		}
	}