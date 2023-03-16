<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\Author;
use App\Models\Book;
use Illuminate\Http\RedirectResponse;
use DataTables;
use Illuminate\Support\Facades\Auth;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('author.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('author.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {

        $validated = $request->validate([
            'name'        =>'required|string|min:3|max:64',
            'surname'     =>'required|string|min:3|max:64'
        ]);
     
        Author::create([
            'name' => $request['name'],
            'surname' => $request['surname'],
        ]);
     
        return redirect('/author/create')->with('message', 'Successfully Saved.');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Author::findOrFail($id);
        $books= Book::whereDoesntHave('authors', function($query) use ($id){
            $query->whereIn('book_id', [$id]);
          })->get();
        return view('author.show', compact('data', 'books'));
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
            $author = Author::findOrFail($id);
            $author->books()->detach();
            $delete = $author->delete();
            if($delete)
                return response(['status'=>'success','message'=> 'Successfull!']);
            else
                return response(['status'=>'error','message'=> 'Error!']);
        }
    }

    public function datatable()
    {
        return Datatables::of(Author::query())->addIndexColumn()
            ->addColumn('action', function($row){
                $user = Auth::user();
                $btn = '<a href="'.route('author.show', $row->id).'" class="btn btn-primary btn-sm mr-1">View</a>';
                if($user->hasRole('ROLE_ADMIN')) $btn .= '<a class="btn btn-danger btn-sm" onclick="author_delete(\''.route('author.destroy', $row->id).'\')" >Delete</a>';
                // $btn .= '<a href="'.route('author.destroy', $row->id).'" class="btn btn-danger btn-sm">Delete</a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function authorbook_datatable($id)
    {
        $author= Author::findOrFail($id);

        return Datatables::of($author->books)->addIndexColumn()
            ->addColumn('action', function($row){
                $btn = '<a href="'.route('book.show', $row->id).'" class="btn btn-primary btn-sm mr-1">View</a>';
                //$btn .= '<a class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to delete?\')" href="'.route('book.destroy', $row->id).'">Delete</a>';
                // $btn .= '<a href="'.route('author.destroy', $row->id).'" class="btn btn-danger btn-sm">Delete</a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function addbook(Request $request)
    {
        $author = Author::findOrFail($request->author_id);
        $author->books()->attach($request->books);
        
        return back()->with('message', 'Successfully Saved.');
    }
}
