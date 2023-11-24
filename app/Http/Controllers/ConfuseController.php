<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Catagory;
use App\Models\Confusion;
use App\Models\Confuse;
use App\Http\Resources\ConfusionResource;
use Illuminate\Database\Eloquent\Builder;

class ConfuseController extends Controller
{
    public function index(Request $request){
      $catagories = Catagory::select('name as label', 'id as value')->get();
      $params = [
        'per_page' => $request->per_page ?? 10,
        'search' => $request->search,
        'catagory_id' => $request->catagory_id,
      ];
      
      $confusions = ConfusionResource::collection(
        Confusion::join('catagories', 'confusions.catagory_id', 'catagories.id')
                  ->select('confusions.id', 'confusions.name', 'catagories.name as catagory')
                  ->when($request->catagory_id, function($join, $cid) {
                    $join->where('confusions.catagory_id', $cid);
                  })
                  ->when($request->search, function($join, $text) {
                    $join->orWhere('confusions.name', 'like', "%$text%")
                    ->orWhereHas('confuses', function(Builder $query) use($text) {
                      $query->where('name', 'like', "%".$text."%")
                            ->orWhere('detail', 'like', "%".$text."%");
                    });
                  })
                  ->with(['confuses' => function($join) use($request) {
                    $join->orderBy('confuses.name', 'asc');
                  }])
                  ->orderBy('confusions.id', 'Desc')
                  ->paginate($params['per_page'])->withQueryString()
      );
      
      return inertia('Confuse/Index', compact('catagories', 'params', 'confusions'));
    }
    
    public function create() {
      $catagories = Catagory::select('name as label', 'id as value')->get();
      return inertia('Confuse/Create', compact('catagories'));
    }
    
    public function store(Request $request) {
      //dd($request);
      $confusions = Confusion::where('catagory_id', $request->catagory_id)->where('name', $request->name)->count();
      if($confusions) return redirect()->back()->with(['toast' => [
        'message' => 'Confusion entry already exist. You batter edit this.',
        'type' => 'error'
      ]]);
      
      $confusion = Confusion::create([
        'catagory_id' => $request->catagory_id,
        'name' => $request->name,
      ]);
      foreach ($request->conf as $conf)
      {
        Confuse::create([
          'confusion_id' => $confusion->id,
          'name' => $conf['name'],
          'catagory' => $conf['catagory'],
          'detail' => $conf['detail'],
        ]);
      }
      
      $toast = [
        'message' => 'Confusion has been store successful.',
        'type' => 'success'
      ];
      return redirect()->route('confuse.index')->with(['toast' => $toast ]);
    }
    
    public function edit($id) {
      $catagories = Catagory::select('name as label', 'id as value')->get();
      $confusion = Confusion::where('id', $id)->with('confuses')->first();
      return inertia('Confuse/Edit', compact('catagories', 'confusion'));
    }
    
    public function update($id, Request $request) {
      Confusion::find($id)->update([
        'name' => $request->name,
        'catagory_id' => $request->catagory_id,
      ]);
      foreach ($request->conf as $conf){
        if(isset($conf['id'])) {
          Confuse::find($conf['id'])->update([
            'name' => $conf['name'],
            'catagory' => $conf['catagory'],
            'detail' => $conf['detail'],
          ]);
        } else {
          Confuse::create([
            'confusion_id' => $id,
            'name' => $conf['name'],
            'catagory' => $conf['catagory'],
            'detail' => $conf['detail'],
          ]);
        }
      }
      if(count($request->deletes) > 0) {
        Confuse::whereIn('id', $request->deletes)->delete();
      }
      
      $toast = [
        'message' => 'Confusion has been updated successful.',
        'type' => 'success'
      ];
      return redirect()->route('confuse.index')->with(['toast' => $toast ]);
    }
    
    public function delete($id) {
      Confusion::find($id)->confuses()->delete();
      Confusion::find($id)->delete();
      $toast = [
        'message' => 'Confusion has been deleted successful.',
        'type' => 'success'
      ];
      return redirect()->route('confuse.index')->with(['toast' => $toast ]);
    }
}
