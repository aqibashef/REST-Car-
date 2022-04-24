<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Year;

class YearController extends Controller
{
    public function setYearByCarId(Request $request, $id){

        $car = Car::find($id);
        if(isset($car)) {
            $requestContent = $request->all();
            if(isset($requestContent['years'])){
                $requestYears = $requestContent['years'];
                foreach($requestYears as $year){
                    if(! Year::where('year', $year)->exists()) {
                        $yearObject = new Year;
                        $yearObject->year = $year;
                        $yearObject->save();
                        $car->years()->attach($yearObject);
                    }
                    else {
                        $yearObject = Year::where('year', $year)->get();
                        $car->years()->attach($yearObject);
                    }
                }

                /** Need More explanation regarding expiry parameter */

                // if(isset($requestContent['expiry'])){
                //     $car->expiry = $requestContent['expiry'];
                //     $car->save();
                // }

                return response(json_encode(array('success' => true)), 200)
                                ->header('Content-type', 'application/json');
            }
            else {
                $response = array(
                    'success'   => false,
                    'message'   => 'Year parameter missing.'
                );
                return response(json_encode($response), 400)
                                ->header('Content-type', 'application/json');
            }
        }
        else {
            $response = array(
                'success'   => false
            );

            return response(json_encode($response), 404)
                            ->header('Content-type', 'application/json');
        }
    }
}
