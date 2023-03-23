<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\ModelCar;
use Hamcrest\Core\HasToString;
use Illuminate\Http\Request;
use App\Repositories\ModelRepository;

class ModelCarController extends Controller
{
    public function __construct(ModelCar $modelCar)
    {
        $this->modelCar = $modelCar;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $modelRepository = new ModelRepository($this->modelCar);

        if ($request->has('attribute_brand')) {
            $attributes_brand = 'brand:id,' . $request->attribute_brand;
            $modelRepository->selectAttribute($attributes_brand);
        } else {
            $modelRepository->selectAttribute('brand');
        }
        if ($request->has('filter')) {
            $modelRepository->filter($request->filter);
        }
        if ($request->has('attribute')) {
            $modelRepository->selectAttributeQuery($request->attribute);
        }
        return response()->json($modelRepository->getResult(), 200);
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
        $request->validate($this->modelCar->rules());
        $image = $request->file('image');
        $image_urn = $image->store('image/modelCars', 'public');

        $modelCar = $this->modelCar->create([
            'brand_id' => $request->brand_id,
            'name' => $request->name,
            'image' => $image_urn,
            'number_of_doors' => $request->number_of_doors,
            'places' => $request->places,
            'air_bag' => $request->air_bag,
            'abs' => $request->abs

        ]);
        return response()->json($modelCar, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ModelCar  $modelCar
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $modelCar = $this->modelCar->with('brand')->find($id);
        if ($modelCar == null) {
            return response()->json(['erro' => 'nenhum registro encontrado'], 404);
        }
        return response()->json($modelCar, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ModelCar  $modelCar
     * @return \Illuminate\Http\Response
     */
    public function edit(ModelCar $modelCar)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ModelCar  $modelCar
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $modelCar = $this->modelCar->find($id);

        if ($modelCar === null) {
            return response()->json(['erro' => 'Não é possível atualizar, registro não existe!'], 404);
        }

        if ($request->method() === 'PATCH') {

            $dynamic_rules = array();

            // Percorrendo as regras definidas no model
            foreach ($modelCar->rules() as $input => $rule) {

                if (array_key_exists($input, $request->all())) {
                    $dynamic_rules[$input] = $rule;
                }
            }

            $request->validate($dynamic_rules);
        } else {
            $request->validate($modelCar->rules());
        }
        //Remove o arquivo antigo, caso o arquivo seja atualizado ou deletado
        if ($request->file('image')) {
            Storage::disk('public')->delete($modelCar->image);
        }
        $image = $request->file('image');
        $image_urn = $image->store('image/modelCars', 'public');

        $modelCar->fill($request->all());
        $modelCar->image = $image_urn;
        $modelCar->save();


        return response()->json($modelCar, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ModelCar  $modelCar
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $modelCar = $this->modelCar->find($id);
        if ($modelCar == null) {
            return response()->json(['erro' => 'Não é possível DELETAR, registro não existe!'], 404);
        }
        // Remove o arquivo antigo, caso o arquivo seja atualizado ou deletado
        Storage::disk('public')->delete($modelCar->image);
        $modelCar->delete();
        return response()->json(['msg' => 'Removido com sucesso'], 200);
    }
}
