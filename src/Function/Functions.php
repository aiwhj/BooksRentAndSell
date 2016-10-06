<?php
function gettime($type,$i) {
	switch ($type) {
		case 'month':
			$data['start']=strtotime("-".$i." months",mktime(0, 0, 0, date("m"), 1, date("Y")));
			$data['end']=strtotime("-".$i." months",mktime(23, 59, 59, date("m"), date("t"), date("Y")));
		break;
		case 'day':
			$data['start']=strtotime("-".$i." days",mktime(0, 0, 0, date("m"), date("d"), date("Y")));
			$data['end']=strtotime("-".$i." days",mktime(23, 59, 59, date("m"), date("d"), date("Y")));
		break;
		case 'year':
			$data['start']=strtotime("-".$i." years",mktime(0, 0, 0, 1, 1, date("Y")));
			$data['end']=strtotime("-".$i." years",mktime(23, 59, 59, 12, date("t"), date("Y")));
		break;
	}
	return $data;
}