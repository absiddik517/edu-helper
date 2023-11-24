<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Books; 
use App\Models\Catagory; 
use App\Http\Requests\Books\CreateRequest;
use App\Http\Requests\Books\UpdateRequest;
use App\Http\Resources\BookResource;
use Illuminate\Support\Str;

class BooksController extends Controller
{
    public function index(Request $request) {
      $params = [
        'search' => $request->input('search') ?? null,
        'name' => $request->input('name') ?? null,
        'author' => $request->input('author') ?? null,
        'per_page' => $request->input('per_page') ?? 5
      ];
      $books = BookResource::collection(Books::join('catagories', 'catagories.id', 'books.catagory_id')
              ->when($request->input('search'), function($join, $text) {
                $join->where('books.name', 'LIKE', "%$text%")
                     ->orWhere('books.author', 'LIKE', "%$text%")
                     ->orWhere('catagories.name', 'LIKE', "%$text%");
              })
              ->when($request->input('name'), function($join, $name) {
                $join->where('books.name', 'LIKE', "$name%");
              })
              ->when($request->input('author'), function($join, $name) {
                $join->where('books.author', 'LIKE', "$name%");
              })
              ->select('books.*', 'catagories.name as catagory_name')
              ->paginate($params['per_page']));
      return inertia('Books/Index', compact('books', 'params'));
    }
    
    public function create() {
      $catagories = Catagory::where('type', 'book')->select('name as label', 'id as value')->get();
      return inertia('Books/Create', compact('catagories'));
    }
    
    public function edit($id) {
      $catagories = Catagory::where('type', 'book')->select('name as label', 'id as value')->get();
      $book = Books::where('id', $id)->first();
      return inertia('Books/Edit', compact('book', 'catagories'));
    }
    
    private function save($data) {
      if(Books::where('name', $data['name'])->where('author', $data['author'])->count() == 0){
        Books::create($data);
        return true;
      }else {
        throw new \Exception('Book already exist');
      }
    }
    
    public function store(CreateRequest $request){
      $names = explode(', ', $request->name);
      $faild = 0;
      $message = "";
      foreach ($names as $name) {
        try {
          $this->save([
            'name' => $name, 
            'author' => $request->author,
            'description' => $request->description,
            'catagory_id' => $request->catagory_id,
          ]);
        } catch (\Exception $e) {
          $faild++;
          $message .= exception_message($e);
        }
      }
      
      $toast = [
        'message' => 'Book has been store successful.',
        'type' => 'success'
      ];
      if($faild) $toast['message'] .= " But $faild ". Str::plural('is', $faild) ." failed.";
      return redirect()->route('books.index')->with(['toast' => $toast]);
      
      
      if(Books::where('name', $request->input('name'))->where('author', $request->input('author'))->count() == 0){
        Books::create($request->validated());
        $toast = [
          'message' => 'Book has been store successful.',
          'type' => 'success'
        ];
        return redirect()->route('books.index')->with(['toast' => $toast]);
      }else {
        $toast = [
          'message' => 'Book already exist.',
          'type' => 'error'
        ];
        return redirect()->back()->with(['toast' => $toast]);
      }
    }
    
    public function update($id, UpdateRequest $request){
      Books::find($id)->update($request->validated());
      $toast = [
        'message' => 'Book has been updated successful.',
        'type' => 'success'
      ];
      return redirect()->route('books.index')->with(['toast' => $toast]);
    }
    
    public function delete($id){
      Books::find($id)->delete();
      $toast = [
        'message' => 'Book has been deleted successful.',
        'type' => 'success'
      ];
      return redirect()->route('books.index')->with(['toast' => $toast]);
    }
}
