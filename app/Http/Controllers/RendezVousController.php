<?php

namespace App\Http\Controllers;

use App\Models\RendezVous;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

class RendezVousController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rendezVous = Cache::rememberForever('allrdv',function(){
             
            return RendezVous::orderBy('id','desc')->get();
        });

        return response()->json($rendezVous);
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
       // Vérifiez si un rendez-vous existe déjà à cette date et heure
       $existingRendezvous = Rendezvous::where('dateRdv', $request->dateRdv)
       ->where('heureDebut', $request->heureDebut)
       ->where('heureFin', $request->heureFin)
       ->exists();
       if ($existingRendezvous) {
        throw ValidationException::withMessages([
            'date_heure' => ['Un rendez-vous existe déjà à cette date et heure.'],
        ]);
    }else{
       $rendezVous = new RendezVous();
       $rendezVous->user_id = Auth::user()->id;
       $rendezVous->dateRdv = $request->dateRdv;
       $rendezVous->heureDebut = $request->heureDebut;
       $rendezVous->heureFin = $request->heureFin;
       $rendezVous->raison = $request->raison;
       $rendezVous->statut = 'en attente';
       $rendezVous->save();
        Cache::forget('NewRdv');
        Cache::forget('allrdv');
        $rendezvous = Cache::rememberForever('allrdv',function(){
             
            return RendezVous::orderBy('id','desc')->get();
        });

        return response()->json(['message' => 'Rendez-vous créé avec succès!', 'rendezvous' => $rendezvous]);
    }
    }

    /**
     * Display the specified resource.
     */
    public function show(RendezVous $rendezVous)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RendezVous $rendezVous)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RendezVous $rendezVous)
    {
        $existingRendezvous = Rendezvous::where('dateRdv', $request->dateRdv)
       ->where('heureDebut', $request->heureDebut)
       ->where('heureFin', $request->heureFin)
       ->exists();
       if ($existingRendezvous) {
        throw ValidationException::withMessages([
            'date_heure' => ['Un rendez-vous existe déjà à cette date et heure.'],
        ]);
    }else {
        if ($request->dateRdv) {
            
            $rendezVous->dateRdv = $request->dateRdv;
        }
        if ($request->heureDebut) {
            
            $rendezVous->heureDebut = $request->heureDebut;
        }
        if ($request->heureFin) {
            
            $rendezVous->heureFin = $request->heureFin;
        }
        if ($request->raison) {
            
            $rendezVous->raison = $request->raison;
        }

        $rendezVous->update();
        Cache::forget('NewRdv');
        Cache::forget('allrdv');
        $rendezvous = Cache::rememberForever('allrdv',function(){
             
            return RendezVous::orderBy('id','desc')->get();
        });
        return response()->json(['message' => 'Rendez-vous modifié avec succès!', 'rendezvous' => $rendezvous]);
    }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RendezVous $rendezVous)
    {
        $rendezVous->delete();
        $rendezvous = Cache::rememberForever('allrdv',function(){
             
            return RendezVous::orderBy('id','desc')->get();
        });
        return response()->json(['message' => 'Rendez-vous supprimé avec succès!', 'rendezvous' => $rendezvous]);
    }
}
