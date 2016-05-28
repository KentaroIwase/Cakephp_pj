<?php

namespace App\Controller;

class PlacesController extends AppController
{
	public function index()
	{
	}

	public function xml()
	{
		$places = $this->Places->find('all');
    $this->set(compact('places'));
		$ne_lat = $this->params['ne_lat'];
		$sw_lat = $this->params['sw_lat'];
		$ne_lng = $this->params['ne_lng'];
		$sw_lng = $this->params['sw_lng'];
	//出力
		header('Content-type: text/xml; charset=utf-8');
		echo '<?xml version="1.0" standalone="yes"?>';
		echo "<Locations>";
		foreach($places as $val){
			if(
				$val->lat < $ne_lat && 
				$val->lat > $sw_lat && 
				$val->lng < $ne_lng && 
				$val->lng > $sw_lng){
			echo "<Locate>";
			echo "<lat>".$val->lat."</lat>";
			echo "<lng>".$val->lng."</lng>";
			echo "<name>".$val->name."</name>";
			echo "</Locate>";
		}
	}
	echo "</Locations>\n";
	}
}