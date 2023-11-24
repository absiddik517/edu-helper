<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Calendar\Event;
use App\Models\Catagory;
use App\Http\Resources\EventResource;
use App\Http\Requests\Event\StoreRequest;
use App\Http\Requests\Event\UpdateRequest;
use App\Helper\Traits\Helper;
use Carbon\Carbon;

class EventController extends Controller
{
  use Helper;
  public function test(){
    return inertia('Event/Test');
  }
  
  public function ck()
  {
    return inertia('Event/Test');
  }
  
  public function index(Request $request){
    
    $params = [
      'per_page' => $request->per_page ?? 10,
      'search' => $request->search,
      'year' => $request->year,
      'month' => $request->month,
      'order' => $request->order,
      'day' => $request->day,
      'catagory_id' => $request->catagory_id,
    ];
    $events = EventResource::collection(Event::join('catagories', 'events.catagory_id', '=', 'catagories.id')
              ->join('users', 'events.user_id', '=', 'users.id')
              ->when($request->search, function($join, $text) {
                $join->where('event', 'like', "%$text%")
                     ->orWhere('detail', 'like', "%$text%");
              })
              ->when($request->year, function($join, $year) {
                $join->where('year', 'LIKE', "$year%")->orWhere('detail', 'like', "%$year%");
              })
              ->when($request->month, function($join, $month) {
                $join->where('month', $month);
              })
              ->when($request->day, function($join, $day) {
                $join->where('day', $day);
              })
              ->when($request->catagory_id, function($join, $catagory_id) {
                $join->where('catagories.id', $catagory_id);
              })
              ->select('events.id', 'year', 'image', 'month', 'day', 'event', 'detail', 'date', 'catagories.name', 'users.name as user_name')
              ->when($request->order, function($join, $order) {
                $join->orderBy('events.year', $order)
                      ->orderByRaw("FIELD(month,'January','February','March','May', 'June','July','August','September','October','November','December')")
                      ->orderBy('events.day', $order);
              })
              ->when(!$request->order, function($join) {
                $join->orderBy('events.id', 'DESC');
              })
              ->paginate($params['per_page'])->withQueryString());
    $catagories = Catagory::select('name as label', 'id as value')->get();
    return inertia('Event/Index', compact('events', 'params', 'catagories'));
  }
  public function create(){
    $catagories = Catagory::select('name as label', 'id as value')->get();
    return inertia('Event/Create', compact('catagories'));
  }
  
  public function edit($id, Request $request){
    $url = $request->url;
    $catagories = Catagory::select('name as label', 'id as value')->get();
    $event = Event::where('id', $id)->first();
    return inertia('Event/Edit', compact('catagories', 'event', 'url'));
  }
  
  public function store(StoreRequest $request){
    //dd($request->file('image'));
    $imagePath = null;
    if ($request->file('image')) {
      $image = $request->file('image');
      $imageName = time() . '.' . $image->getClientOriginalExtension();
      $image->move(public_path('uploads'), $imageName);
      $imagePath = 'uploads/'.$imageName;
    }
    
    
    
    $data = [
      'year' => $request->year,
      'month' => $request->month,
      'day' => $request->day,
      'catagory_id' => $request->catagory_id,
      'event' => $request->event,
      'detail' => $request->detail,
      'image' => $imagePath,
    ];
    if($request->year && $request->month && $request->day){
      $data['date'] = date('Y-m-d', strtotime($request->year.'-'.$this->months[$request->month].'-'.$request->day));
    }
    //dd($data);
    Event::create($data);
    $toast = [
      'message' => 'Event has been store successful.',
      'type' => 'success'
    ];
    return redirect()->route('calender.event.index')->with(['toast' => $toast ]);
  }
  
  public function update($id, UpdateRequest $request){
    $imagePath = null;
    if ($request->hasFile('file')) {
      $image = $request->file('file');
      $imageName = time() . '.' . $image->getClientOriginalExtension();
      $image->move(public_path('uploads'), $imageName);
      $imagePath = 'uploads/'.$imageName;
    }
    
    $data = [
      'year' => $request->year,
      'month' => $request->month,
      'day' => $request->day,
      'catagory_id' => $request->catagory_id,
      'event' => $request->event,
      'detail' => $request->detail,
      'image' => $imagePath,
    ];
    if($request->year && $request->month && $request->day){
      $data['date'] = date('Y-m-d', strtotime($request->year.'-'.$this->months[$request->month].'-'.$request->day));
    }
    Event::find($id)->update($data);
    $toast = [
      'message' => 'Event has been updated successful.',
      'type' => 'success'
    ];
    $route = $request->input('url') ?? route('calender.event.index');
    return redirect()->to($route)->with(['toast' => $toast ]);
  }
  
  public function delete($id){
    $event = Event::where('id', $id)->first();
    if($event->image){
      unlink($event->image);
    }
    $event->delete();
    $toast = [
      'message' => 'Event has been deleted successful.',
      'type' => 'success'
    ];
    return redirect()->back()->with(['toast' => $toast ]);
  }
  
}
