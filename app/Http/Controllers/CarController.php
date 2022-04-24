<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Year;

class CarController extends Controller
{

    public function getCars(Request $request) {
        $requestContent = $request->all();
        $response = array();
        $cars = null;
        $yearObjects = null;
        if(isset($requestContent['years'])){
            $years = explode(',', $requestContent['years']);
            $yearObjects = Year::whereIn('year', $years)->get('year');
            if(isset($yearObjects)){
                $cars = Car::whereHas('years', function($query) use($yearObjects){
                    $query->whereIn('year', $yearObjects);
                })->get();
            }
        }
        else {
            $cars = Car::all();
        }
        if(isset($cars)){
            foreach($cars as $car){
                $carResponse = array(
                    'id'        => $car->id,
                    'info'      => $car->make .' '. $car->model
                );
                $carYears = $car->years()->get()->toArray();
                if(isset($carYears)){
                    $carResponseYears = array_map(function($c){ return $c['year'];}, $carYears);
                    $carResponse['years'] = $carResponseYears;
                    if(isset($requestContent['years'])){
                        $carResponse['info'] .= ' '. $carYears[0]['year'];
                    }
                }
                array_push($response, $carResponse);
            }
        }
        else {
            $response['message'] = 'There is no cars';
        }
        return response(json_encode($response))
                        ->header('Content-type', 'application/json');
    }

    public function getCarById($id){
        $car = Car::find($id);
        $response = array(
            'id'    => $car->id,
            'info'  => $car->make . ' ' . $car->model,
        );

        if(isset($car->years)){
            $yearObjects = $car->years()->get()->toArray();
            $response['years'] = array_map(function($c){ return $c['year']; }, $yearObjects);
        }

        return response(json_encode($response), 200)
                        ->header('Content-type', 'application/json');
    }

    public function store(Request $request) {
        $response = array();
        $requestContent = $request->all();
        if(! isset($requestContent['make'])) {
            $response['message'] = 'You need to provide make parameter';
            return response(json_encode($response), 400)
                            ->header('Content-type', 'application/json');
        }
        if(! isset($requestContent['model'])) {
            $response['message'] = 'You need to provide model parameter';
            return response(json_encode($response), 400)
                            ->header('Content-type', 'application/json');
        }

        try {
            $make = $requestContent['make'];
            $model = $requestContent['model'];
            $car = new Car;
            $car->make = $make;
            $car->model = $model;
            // $car->created_at = Carbon::now();
            $car->save();

            $response = array(
                'id' => $car->id
            );
        }
        catch(Exception $e){
            $response['message'] = 'The entry probably already exists in Database.';
            return response(json_encode($response), 400)
                            ->header('Content-type', 'application/json');
        }

        return response(json_encode($response), 200)
                        ->header('Content-type', 'application/json');
    }
}
