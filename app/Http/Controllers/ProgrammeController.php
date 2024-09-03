<?php

namespace App\Http\Controllers;

use App\Models\Programme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProgrammeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $programmes = Cache::rememberForever('allprog', function(){
             
            return Programme::orderBy('id','desc')->get();
        });
        return response()->json($programmes);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $programme = new Programme();
        $programme->pays_id = $request->pays_id;
        $programme->titre = $request->titre;
        $programme->type = $request->type;
        $programme->description = $request->description;
        $programme->conditions = json_encode(['Diplôme de fin d\'études', 'Preuve de fonds']);
        $programme->frais = $request->frais;
        $programme->duree = $request->duree;
        $programme->delais = $request->delais;
        $programme->statut = $request->statut;
        $programme->nombre_de_places = $request->nombre_de_places;
        $programme->save();
        Cache::forget('Newprog');
        Cache::forget('allprog');

        $programmes = Cache::rememberForever('allprog', function(){
             
            return Programme::orderBy('id','desc')->get();
        });
        return response()->json($programmes);
    }

    /**
     * Display the specified resource.
     */
    public function show(Programme $programme)
    {
        return response()->json($programme);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Programme $programme)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Programme $programme)
    {
        if (request('pays_id')) 
        {
            $programme->pays_id = $request->pays_id;
        }
        if (request('titre')) 
        {
            $programme->titre = $request->titre;
        }
        if (request('type')) {
            $programme->type = $request->type;
        }
        if (request('description')) {
            
            $programme->description = $request->description;
        }   
        $programme->conditions = json_encode(['Diplôme de fin d\'études', 'Preuve de fonds']);
        if (request('frais')) {
            
            
            $programme->frais = $request->frais;
        }
        if (request('duree')) {
           
            $programme->duree = $request->duree;
        }
        if (request('delais')) {
           
           
            $programme->delais = $request->delais;
        }
        if (request('statut')) {
           
            $programme->statut = $request->statut;
        }
        if (request('nombre_de_places')) {
        
            $programme->nombre_de_places = $request->nombre_de_places;
        }
        $programme->update();
        Cache::forget('Newprog');
        Cache::forget('allprog');
         
        return response()->json($programme);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Programme $programme)
    {
        $programme->delete();
        Cache::forget('Newprog');
        Cache::forget('allprog');
        $programmes = Cache::rememberForever('allprog', function(){
             
            return Programme::orderBy('id','desc')->get();
        });
        return response()->json($programmes);
    }
}
