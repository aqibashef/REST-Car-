<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\Car;

class CarController extends Controller
{

    public function getCars() {
        $cars = Car::all();
        return response(json_encode($cars))
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
