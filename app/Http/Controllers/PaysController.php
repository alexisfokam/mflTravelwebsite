<?php

namespace App\Http\Controllers;

use App\Models\Pays;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PaysController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $country = Cache::rememberForever('allpays', function(){
            return Pays::orderBy('created_at','asc')->get();
        });
        return response()->json($country);
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
        $pays = new Pays();
        $pays->name = $request->name;
        $pays->superficie = $request->superficie;
        $pays->nbr_habitant = $request->nbr_habitant;
        $pays->langue = $request->langue;
        $pays->description = $request->description;
        $image = $request->file('photo');
        $extension = $image->getClientOriginalExtension();
        $newFileName = time().'.'.$extension;
         $image->move(public_path('country_img'), $newFileName);
        $request['photo'] = $newFileName;
        $pays->photo = $newFileName;
        $pays->save();
        Cache::forget('NewPays');
        Cache::forget('allpays');
        $country = Cache::rememberForever('allpays',function(){
            return Pays::orderBy('created_at','asc')->get();
        });
    
        return response()->json(['message'=>'country created successfully','pays'=>$country]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Pays $pays)
    {
        return response()->json($pays);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pays $pays)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updatePays(Request $request,Pays $pays)
    {
       if ($request->name) {
          $pays->name = $request->name;
       }
       if ($request->superficie) {
        $pays->superficie = $request->superficie;
     }
     if ($request->nbr_habitant) {
        $pays->nbr_habitant = $request->nbr_habitant;
     }
     if ($request->langue) {
        $pays->langue = $request->langue;
     }
     if ($request->description) {
        $pays->description = $request->description;
     }
        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $extension = $image->getClientOriginalExtension();
            $newFileName = time().'.'.$extension;
             $image->move(public_path('country_img'), $newFileName);
            $request['photo'] = $newFileName;
            $pays->photo = $newFileName;
        }
    
        $pays->update();
        
        return response()->json($pays);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pays $pays)
    {
        $pays->delete();

        Cache::forget('NewPays');
        Cache::forget('allPays');
    $country = Cache::rememberForever('allpays', function(){
        return Pays::orderBy('created_at','asc')->get();
    });
   
    return response()->json($country);
    }
}

