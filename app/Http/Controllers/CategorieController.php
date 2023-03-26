<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories= Categorie::all();
        return $categories->toJson();
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
        'titre'=>'required'
    ]);
    
    if($validator->fails()) {
        return response()->json($validator->errors(), 400);
    }
        $data= $request->all();
        Categorie::create($data);
        return response()->json( $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cat= Categorie::find($id);
        if(!$cat){
            return response()->json(['categorie_not_found'], 404);}
        return $cat->toJson();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cat= Categorie::find($id);
        if(!$cat){
            return response()->json(['categorie_not_found'], 404);}
        return $cat->toJson();
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
            'titre'=>'required'
        ]);
        
        if($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $cat= Categorie::find($id);
        if(!$cat){
            return response()->json(['categorie_not_found'], 404);
        }
        $data=$request->all();
        $cat->update($data);
        // $cat= Categorie::find($id);
        return response()->json($cat);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cat= Categorie::destroy($id);
        if(!$cat){
            return response()->json(['categorie_not_found'], 404);}
        return 'delete categorie id:'.$id;
    }
}
