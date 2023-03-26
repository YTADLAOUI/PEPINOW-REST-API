<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
// use JWTAuth;

class PlantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $plant= Plant::all();
        $plants = Plant::with(['categorie', 'users'])->get();

        foreach ($plants as $plant) {
            $plant->categorie_id = $plant->categorie->titre;
            $plant->user_id = $plant->users->name;
            $plants->makeHidden(['categorie', 'users']);
        }
        return $plants->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return 'page de product';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'prix' => 'required',
            'categorie_id' => 'required',
            'image' => 'required',
        ]);
        
        if($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        
        $data= $request->all();
        // return $data;
        $user=Auth::user();
        $data['user_id'] = $user->id;
        Plant::create($data);
        return $data;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        $plant= Plant::find($id);
        if(!$plant){
            return response()->json(['plant_not_found'], 404);
        }
        return $plant->toJson();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $plant= Plant::find($id);
        return $plant->toJson();
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
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'prix' => 'required',
            'categorie_id' => 'required',
            'image' => 'required',
        ]);
        
        if($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $plant= Plant::find($id);
        if(!$plant){
            return response()->json(['plant_not_found'], 404);
        }
        $data=$request->all();
        $user=Auth::user();
        $data['user_id'] = $user->id;
        $plant->update($data);
        return response()->json($plant);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $plant= Plant::destroy($id);
        if(!$plant){
            return response()->json(['plant_not_found'], 404);
        }
        return 'delete plant id:'.$id;
    }
}
