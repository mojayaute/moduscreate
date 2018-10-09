<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class vehiclesController extends Controller
{
    

    public function getCars(Request $request, $model_year, $manufacturer, $model )
    {
    	$input = $request->all();
    	$model_year = $model_year=='undefined'? '' : $model_year;
    	$manufacturer = $manufacturer=='undefined'? '' : $manufacturer;
    	$model = $model=='undefined'? '' : $model;

    	$client = new Client();
		$res = $client->request('GET', 'https://one.nhtsa.gov/webapi/api/SafetyRatings/modelyear/'.$model_year.'/make/'.$manufacturer.'/model/'.$model.'?format=json');
		$payload = json_decode($res->getBody()->getContents(), true);
    	if($payload){
			foreach($payload as $key => $element) {
			   if("Message" == $key){
			      unset($payload[$key]);
			   }
			}

			if(isset($input['withRating'])){
	    		if($input['withRating'] === 'true'){
	    			$carga = $this->getCrashRatings($payload);
	    			return json_encode($carga);
	    		}
	    	}
			return json_encode($payload);
		}else{
			return json_encode(['Results'=>[],'Count'=>0]);
		}
    }

    public function postCar(Request $request)
    {
    	
    	$input = $request->input();
    	if(!$input){
    		$content = json_decode($request->getContent(), true);

    		$model_year = isset($content['modelYear'])? $content['modelYear'] : '';
	    	$manufacturer = isset($content['manufacturer'])? $content['manufacturer'] : '';
	    	$model = isset($content['model'])? $content['model'] : '';
	    	$model_year = $model_year=='undefined'? '' : $model_year;
	    	$manufacturer = $manufacturer=='undefined'? '' : $manufacturer;
	    	$model = $model=='undefined'? '' : $model;
    	}else{
    		$model_year = isset($input['modelYear'])? $input['modelYear'] : '';
	    	$manufacturer = isset($input['manufacturer'])? $input['manufacturer'] : '';
	    	$model = isset($input['model'])? $input['model'] : '';
	    	$model_year = $model_year=='undefined'? '' : $model_year;
	    	$manufacturer = $manufacturer=='undefined'? '' : $manufacturer;
	    	$model = $model=='undefined'? '' : $model;
    	}
    	
    	
    	$client = new Client();
		$response = $client->request('GET', 'https://one.nhtsa.gov/webapi/api/SafetyRatings/modelyear/'.$model_year.'/make/'.$manufacturer.'/model/'.$model.'?format=json');
		$payload = json_decode($response->getBody()->getContents(), true);
    	if($payload){
			foreach($payload as $key => $element) {
			   if("Message" == $key){
			      unset($payload[$key]);
			   }
			}
			return json_encode($payload);
		}else{
			return json_encode(['Results'=>[],'Count'=>0]);
		}
    	
    }

    public function getCrashRatings($payload){

    	
    	if(sizeof($payload) > 0 && isset($payload['Results'])){
	    	foreach ($payload['Results'] as $key => $value) {
	    		if( $key == 'VehicleId'){
	    			$client = new Client();
	    			$vehicleid = $value['VehicleId'];
					$response = $client->request('GET', 'https://one.nhtsa.gov/webapi/api/SafetyRatings/VehicleId/'.$vehicleid.'?format=json');
					$payloadWithRating = json_decode($response->getBody()->getContents(), true);
					
					$overallRating = $payloadWithRating['Results']['0']['OverallRating'];
					$results = [];
					foreach ($payload as $key => $value) {
						if($key == 'Results'){
							foreach ($value as $j => $val) {
								$reg = ['CrashRating'=>$overallRating];
								foreach ($val as $l => $va) {
									$reg[$l] = $va;
								}
								$results[] =$reg;
							}
						}
						$payload['Results'] = $results;
					}
					return $payload;
	    		}
	    	}
    	}	
    	
    }

}
