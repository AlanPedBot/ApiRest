<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function __construct(Brand $brand)
    {
        $this->brand = $brand;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $brand = Brand::all();
        $brands = $this->brand->with('modelCar')->get();
        return response()->json($brands, 200);
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
        // $brand = Brand::create($request->all());

        $request->validate($this->brand->rules(), $this->brand->feedback());
        $image = $request->file('image');
        $image_urn = $image->store('image', 'public');

        $brand = $this->brand->create([
            'name' => $request->name,
            'image' => $image_urn
        ]);
        return response()->json($brand, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $brand = $this->brand->with('modelCar')->find($id);
        if ($brand == null) {
            return response()->json(['erro' => 'nenhum registro encontrado'], 404);
        }
        return response()->json($brand, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit(Brand $brand)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer
     * @return \Illuminate\Http\Response
     */


    public function update(Request $request, $id)
    {

        $brand = $this->brand->find($id);

        if ($brand === null) {
            return response()->json(['erro' => 'Não é possível atualizar, registro não existe!'], 404);
        }

        if ($request->method() === 'PATCH') {

            $dynamic_rules = array();

            // Percorrendo as regras definidas no model
            foreach ($brand->rules() as $input => $rule) {

                if (array_key_exists($input, $request->all())) {
                    $dynamic_rules[$input] = $rule;
                }
            }

            $request->validate($dynamic_rules, $brand->feedback());
        } else {
            $request->validate($brand->rules(), $brand->feedback());
        }
        //Remove o arquivo antigo, caso o arquivo seja atualizado ou deletado
        // dd($request->file('image'));
        if ($request->file('image')) {
            Storage::disk('public')->delete($brand->image);
        }
        $image = $request->file('image');
        $image_urn = $image->store('image', 'public');

        //preencher o objeto com os dados do request
        $brand->fill($request->all());
        $brand->image = $image_urn;
        $brand->save();

        return response()->json($brand, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */



    public function destroy($id)
    {
        $brand = $this->brand->find($id);
        if ($brand == null) {
            return response()->json(['erro' => 'Não é possível DELETAR, registro não existe!'], 404);
        }
        // Remove o arquivo antigo, caso o arquivo seja atualizado ou deletado
        Storage::disk('public')->delete($brand->image);
        $brand->delete();
        return response()->json(['msg' => 'Removido com sucesso'], 200);
    }
}
