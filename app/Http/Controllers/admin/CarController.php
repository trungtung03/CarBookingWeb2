<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Car;

class CarController extends Controller
{
    public function index()
    {
        $cars = Car::all();
        return response()->json([
            'status' => true,
            'cars' => $cars
        ]);
    }

    public function store(Request $request)
    {
        $validateCar = Validator::make(
            $request->all(),
            [
                'departure_location' => 'required',
                'destination' => 'required',
                'name' => 'required',
                'license_plates' => 'required|unique:cars',
                'image' => 'required',
                'price' => 'required|numeric',
                'type_name' => 'required',
                'id_user' => 'required|exists:users,id'
            ]
        );

        if ($validateCar->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validateCar->errors()
            ], 401);
        }

        $car = Car::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Car added successfully',
            'car' => $car
        ]);
    }

    public function update(Request $request, $id)
    {
        $car = Car::find($id);

        if (!$car) {
            return response()->json([
                'status' => false,
                'message' => 'Car not found'
            ], 404);
        }

        $validateCar = Validator::make(
            $request->all(),
            [
                'departure_location' => 'required',
                'destination' => 'required',
                'name' => 'required',
                'license_plates' => 'required|unique:cars,license_plates,' . $car->id,
                'image' => 'required',
                'price' => 'required|numeric',
                'type_name' => 'required',
                'id_user' => 'required|exists:users,id'
            ]
        );

        if ($validateCar->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validateCar->errors()
            ], 401);
        }

        $car->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Car updated successfully',
            'car' => $car
        ]);
    }

    public function destroy($id)
    {
        $car = Car::find($id);

        if (!$car) {
            return response()->json([
                'status' => false,
                'message' => 'Car not found'
            ], 404);
        }

        $car->delete();

        return response()->json([
            'status' => true,
            'message' => 'Car deleted successfully'
        ]);
    }
}
