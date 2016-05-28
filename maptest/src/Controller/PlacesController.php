<?php

namespace App\Controller;

class PlacesController extends AppController
{
	public function index()
	{
	}

	public function xml()
	{
		$places = $this->Places->find('all')
													 ->where(function ($exp, $q) {
        											return $exp->lte('lat', $this->request->query('ne_lat'))
        																 ->gte('lat', $this->request->query('sw_lat'))
        																 ->lte('lng', $this->request->query('ne_lng'))
        																 ->gte('lng', $this->request->query('sw_lng'));
    												});
		$this->set([
			'places' => $places,
			'_serialize' => ['places']
		]);
	}
}