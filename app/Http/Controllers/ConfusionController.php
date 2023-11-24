<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Catagory;
use App\Models\Confusion;
use App\Http\Resources\ConfusionResource;

class ConfusionController extends Controller
{
    public function index($id, Request $request){
      $confusion = ConfusionResource::make(
        Confusion::join('catagories', 'confusions.catagory_id', 'catagories.id')
                  ->select('confusions.id', 'confusions.name', 'catagories.name as catagory')
                  ->where('confusions.id', $id)
                  ->with(['confuses' => function($join) {
                    $join->orderBy('confuses.detail', 'asc');
                  }])
                  ->first()
      );
      
      return inertia('Confusion/Index', compact('confusion'));
    }
    
    public function create() {
      $catagories = Catagory::select('name as label', 'id as value')->get();
      return inertia('Confusion/Create', compact('catagories'));
    }
    
    public function store(Request $request) {
      Confusion::create([
        'catagory_id' => $request->catagory_id,
        'name' => $request->name,
      ]);
      $toast = [
        'message' => 'Confusion has been store successful.',
        'type' => 'success'
      ];
      return redirect()->route('confuse.index')->with(['toast' => $toast ]);
    }
}
