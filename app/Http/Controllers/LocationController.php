<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use App\Repositories\BrandRepository;

class LocationController extends Controller
{
    public function __construct(Location $location)
    {
        $this->location = $location;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $locationRepository = new BrandRepository($this->location);

        if ($request->has('filter')) {
            $locationRepository->filter($request->filter);
        }
        if ($request->has('attribute')) {
            $locationRepository->selectAttributeQuery($request->attribute);
        }
        return response()->json($locationRepository->getResult(), 200);
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
        $request->validate($this->location->rules());
        $location = $this->location->create([
            'client_id' => $request->client_id,
            'car_id' => $request->car_id,
            'period_start_date' => $request->period_start_date,
            'end_date_expected_period' => $request->end_date_expected_period,
            'end_date_performed_period' => $request->end_date_performed_period,
            'daily_value' => $request->daily_value,
            'km_initial' => $request->nakm_initialme,
            'km_final' => $request->km_final
        ]);
        return response()->json($location, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $location = $this->location->find($id);
        if ($location == null) {
            return response()->json(['erro' => 'nenhum registro encontrado'], 404);
        }
        return response()->json($location, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function edit(Location $location)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $location = $this->location->find($id);

        if ($location === null) {
            return response()->json(['erro' => 'Não é possível atualizar, registro não existe!'], 404);
        }

        if ($request->method() === 'PATCH') {

            $dynamic_rules = array();

            // Percorrendo as regras definidas no model
            foreach ($location->rules() as $input => $rule) {

                if (array_key_exists($input, $request->all())) {
                    $dynamic_rules[$input] = $rule;
                }
            }

            $request->validate($dynamic_rules);
        } else {
            $request->validate($location->rules());
        }
        //Remove o arquivo antigo, caso o arquivo seja atualizado ou deletado
        // dd($request->file('image'));

        //preencher o objeto com os dados do request
        $location->fill($request->all());
        $location->save();

        return response()->json($location, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $location = $this->location->find($id);
        if ($location == null) {
            return response()->json(['erro' => 'Não é possível DELETAR, registro não existe!'], 404);
        }
        $location->delete();
        return response()->json(['msg' => 'Removido com sucesso'], 200);
    }
}
