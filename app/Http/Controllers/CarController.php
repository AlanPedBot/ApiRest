<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use App\Repositories\CarRepository;

class CarController extends Controller
{
    public function __construct(Car $car)
    {
        $this->car = $car;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $carRepository = new CarRepository($this->car);

        if ($request->has('attribute_model')) {
            $attributes_model = 'modelCar:id,' . $request->attribute_model;
            $carRepository->selectAttribute($attributes_model);
        } else {
            $carRepository->selectAttribute('modelCar');
        }
        if ($request->has('filter')) {
            $carRepository->filter($request->filter);
        }
        if ($request->has('attribute')) {
            $carRepository->selectAttributeQuery($request->attribute);
        }
        return response()->json($carRepository->getResult(), 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->car->rules());
        $car = $this->car->create([
            'modelCar_id' => $request->modelCar_id,
            'plate' => $request->plate,
            'available' => $request->available,
            'km' => $request->km
        ]);
        return response()->json($car, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $car = $this->car->with('modelCar')->find($id);
        if ($car === null) {
            return response()->json(['erro' => 'nenhum registro encontrado'], 404);
        }
        return response()->json($car, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function edit(Car $car)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $car = $this->car->find($id);

        if ($car === null) {
            return response()->json(['erro' => 'Não é possível atualizar, registro não existe!'], 404);
        }

        if ($request->method() === 'PATCH') {

            $dynamic_rules = array();

            // Percorrendo as regras definidas no model
            foreach ($car->rules() as $input => $rule) {

                if (array_key_exists($input, $request->all())) {
                    $dynamic_rules[$input] = $rule;
                }
            }

            $request->validate($dynamic_rules);
        } else {
            $request->validate($car->rules());
        }
        //Remove o arquivo antigo, caso o arquivo seja atualizado ou deletado
        // dd($request->file('image')

        //preencher o objeto com os dados do request
        $car->fill($request->all());
        $car->save();

        return response()->json($car, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $car = $this->car->find($id);
        if ($car === null) {
            return response()->json(['erro' => 'Não é possível DELETAR, registro não existe!'], 404);
        }
        $car->delete();
        return response()->json(['msg' => 'Carro removido com sucesso'], 200);
    }
}
