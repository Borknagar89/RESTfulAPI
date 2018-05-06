<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use Input;
//use Redirect;
use Log;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all()->toArray();//Llama todos los usuarios y los deja en un array
        return response()->json($users);//Envia los datos en formato json
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
        try{
            $user = new User([
                'name' => $request->input('username'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
            ]);
            Log::info('User stored');
            $user->save();
            return response()->json(['status'=>true,'mensaje' => 'Gracias'],200);
        }catch(\Exception $e){
            Log::critical("Error: {$e->getCode()},{$e->getLine()},{$e->getMessage()}");
            return response('Something bad',500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            $user = User::find($id);

            if(!$user){
                return response()->json(['Este id no existe'],404);
            }

            return response()->json($user,200); 

        }catch(\Exception $e){
            Log::critical("No se pudo guardar el usuario: {$e->getCode()}, {$e->getLine()}, {$e->getMessage()}");
            return response('Something bad',500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $user->update($request->all());
        return ['Usuario actualizado' => true];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $user = User::find($id);

            if(!$user){
                return response()->json(['Este id no existe'],404);
            }

            $user->delete();
            return response()->json('Usuario borrado',200);

        }catch(\Exception $e){
            Log::critical("No se pudo eliminar: {$e->getCode()}, {$e->getLine()}, {$e->getMessage()}");
            return response('Somethin bad',500);
        }
    }
}
