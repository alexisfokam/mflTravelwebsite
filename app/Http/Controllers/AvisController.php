<?php

namespace App\Http\Controllers;

use App\Jobs\ResetAvisJob;
use App\Jobs\SendAvisNotification;
use App\Models\Avis;
use App\Models\Programme;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class AvisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $avis = Cache::rememberForever('allvis', function(){
            return Avis::orderBy('id','desc')->get();
        });

        return response()->json($avis);
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
        $avis = new Avis();
        $avis->user_id = Auth::user()->id;
        $avis->programme_id = $request->programme_id;
        $avis->note = $request->note;
        $avis->commentaire = $request->commentaire;
        $avis->date_creation = Carbon::now();
        $avis->statut = 'actif';
        $avis->save();
         
        $item = Cache::rememberForever('allvis', function(){
            return Avis::orderBy('id','desc')->get();
        });
         // Dispatcher le job pour envoyer la notification
         SendAvisNotification::dispatch($avis);

         return response()->json(['message' => 'Avis créé avec succès!', 'review' => $item]);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Avis $avis)
    {
        return response()->json($avis); 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Avis $avis)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Avis $avis)
    {
       if (request('note')) {
          $avis->note = $request->note;
       }
       if (request('commentaire')) {
        $avis->commentaire = $request->commentaire;
     }
     $avis->date_creation = Carbon::now();
     $avis->update();
      Cache::forget('new_avis');
      Cache::forget('allavis');
      $item = Cache::rememberForever('allavis', function (){
        return Avis::orderBy('id', 'desc')->get();
      });
     return response()->json($item);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Avis $avis)
    {
        $avis->delete();
        $item = Cache::rememberForever('allavis', function (){
            return Avis::orderBy('id', 'desc')->get();
          });
          
         return response()->json($item);
    }
    public function switchAvis($id)
    {

        $avis = Avis::find($id);
        if ($avis->statut == "actif") { //actif
            $avis->statut = "inactif";
            $avis->update();
        } else {                  //deactif
           $avis->statut = "actif";
           $avis->update();
        }

        $user = User::select('*')->where('id', $avis->user_id)->first();

        // SwitchPublicationJob::dispatch($pub,$user);
        ResetAvisJob::dispatch($user);
        cache::forget('allavis');
        cache::forget('mypubs-' . $user);
        $publications =  Cache::rememberForever('allavis', function () {
            return Avis::orderBy('id', 'DESC')->get();
        });

        return response()->json($publications);
    }

}
