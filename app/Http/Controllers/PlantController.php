<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use Illuminate\Http\Request;
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
        $plant= Plant::all();
        return $plant->toJson();
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
        $request->validate([
            'name'=>'required',
            'prix'=>'required',
            'categorie_id'=>'required',
            
        ]);
        $data= $request->all();
        $user=Auth::user();
        $data['user_id'] = $user->id;
        Plant::create($data);
        return 'data add sucuss';
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
        // $request->validate([
        //     'name'=>'required',
        //     'prix'=>'required',
        //     'categorie_id'=>'required', 
        // ]);
        $plant= Plant::find($id);
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
        return 'delete plant id:'.$id;
    }
}
