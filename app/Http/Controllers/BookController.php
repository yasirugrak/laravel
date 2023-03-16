<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\Book;
use App\Models\Author;
use Illuminate\Http\RedirectResponse;
use DataTables;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('book.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('book.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if($user->hasRole('ROLE_ADMIN')){
            $validated = $request->validate([
                'isbn'        =>'required|string|min:10|max:13',
                'name'        =>'required|string|min:3|max:255',
                'detail'      =>'',
                'price'       =>'required|regex:/^\d+(\.\d{1,2})?$/',
                'quantity'    =>'required|numeric|min:0'
            ]);
         
            Book::create([
                'isbn' => $request['isbn'],
                'name' => $request['name'],
                'detail' => $request['detail'],
                'price' => $request['price'],
                'quantity' => $request['quantity'],
            ]);
         
            return redirect('/book/create')->with('message', 'Successfully Saved.');
        }else{
            echo "you are not authorized";
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Book::findOrFail($id);
        $authors= Author::whereDoesntHave('books', function($query) use ($id){
            $query->whereIn('author_id', [$id]);
          })->get();
        return view('book.show', compact('data', 'authors'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Auth::user();
        if($user->hasRole('ROLE_ADMIN')){
            $book = Book::findOrFail($id);
            $book->authors()->detach();
            $delete = $book->delete();
            if($delete)
                return response(['status'=>'success','message'=> 'Successfull!']);
            else
                return response(['status'=>'error','message'=> 'Error!']);
        }
       
    }

    public function datatable()
    {
        return Datatables::of(Book::query())->addIndexColumn()
            ->addColumn('action', function($row){
                $user = Auth::user();
                $btn = '<a href="'.route('book.show', $row->id).'" class="btn btn-primary btn-sm mr-1">View</a>';
                if($user->hasRole('ROLE_ADMIN')) $btn .= '<a class="btn btn-danger btn-sm" onclick="book_delete(\''.route('book.destroy', $row->id).'\')" >Delete</a>';
                
                // $btn .= '<a href="'.route('author.destroy', $row->id).'" class="btn btn-danger btn-sm">Delete</a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function bookauthor_datatable($id)
    {
        $book= Book::findOrFail($id);

        return Datatables::of($book->authors)->addIndexColumn()
            ->addColumn('action', function($row){
                $btn = '<a href="'.route('author.show', $row->id).'" class="btn btn-primary btn-sm mr-1">View</a>';
                //$btn .= '<a class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to delete?\')" href="'.route('author.destroy', $row->id).'">Delete</a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
