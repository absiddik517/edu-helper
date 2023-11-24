<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Catagory;
use App\Models\Calendar\Event;
use App\Http\Requests\Catagory\StoreRequest;
use App\Http\Requests\Catagory\UpdateRequest;

class CatagoryController extends Controller
{
  public function index(){
    $catagories = Catagory::get();
    return inertia('Catagory/Index', compact('catagories'));
  }
  public function create(){
    return inertia('Catagory/Create');
  }
  public function edit($id){
    $catagory = Catagory::where('id', $id)->first();
    return inertia('Catagory/Edit', compact('catagory'));
  }
  
  public function store(StoreRequest $request){
    Catagory::create($request->validated());
    $toast = [
      'message' => 'Catagory has been store successful.',
      'type' => 'success'
    ];
    return redirect()->route('calender.catagory.index')->with(['toast' => $toast]);
  }
  
  public function update($id, UpdateRequest $request){
    Catagory::find($id)->update($request->validated());
    $toast = [
      'message' => 'Catagory has been updated successful.',
      'type' => 'success'
    ];
    return redirect()->route('calender.catagory.index')->with(['toast' => $toast]);
  }
  
  public function delete($id){
    $used = Event::where('catagory_id', $id)->count();
    if(!$used) {
      Catagory::find($id)->delete();
      $toast = [
        'message' => 'Catagory has been updated successful.',
        'type' => 'success'
      ];
    }else{
      $toast = [
        'message' => 'Catagory can not be deleted as it is already been used.',
        'type' => 'error'
      ];
    }
    return redirect()->route('calender.catagory.index')->with(['toast' => $toast]);
  }
}
