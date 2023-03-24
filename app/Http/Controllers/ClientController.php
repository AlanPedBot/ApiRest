<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Repositories\ClientRepository;

class ClientController extends Controller
{
    public function __construct(Client $client)
    {
        $this->client = $client;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $clientRepository = new ClientRepository($this->client);

        if ($request->has('filter')) {
            $clientRepository->filter($request->filter);
        }
        if ($request->has('attribute')) {
            $clientRepository->selectAttributeQuery($request->attribute);
        }
        return response()->json($clientRepository->getResult(), 200);
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
        $request->validate($this->client->rules());
        $client = $this->client->create([
            'name' => $request->name
        ]);
        return response()->json($client, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client = $this->client->find($id);
        if ($client === null) {
            return response()->json(['erro' => 'nenhum registro encontrado'], 404);
        }
        return response()->json($client, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $client = $this->client->find($id);

        if ($client === null) {
            return response()->json(['erro' => 'Não é possível atualizar, registro não existe!'], 404);
        }

        if ($request->method() === 'PATCH') {

            $dynamic_rules = array();

            // Percorrendo as regras definidas no model
            foreach ($client->rules() as $input => $rule) {

                if (array_key_exists($input, $request->all())) {
                    $dynamic_rules[$input] = $rule;
                }
            }

            $request->validate($dynamic_rules);
        } else {
            $request->validate($client->rules());
        }
        //Remove o arquivo antigo, caso o arquivo seja atualizado ou deletado
        // dd($request->file('image')

        //preencher o objeto com os dados do request
        $client->fill($request->all());
        $client->save();

        return response()->json($client, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client = $this->client->find($id);
        if ($client === null) {
            return response()->json(['erro' => 'Não é possível DELETAR, registro não existe!'], 404);
        }
        $client->delete();
        return response()->json(['msg' => 'Cliente removido com sucesso'], 200);
    }
}
