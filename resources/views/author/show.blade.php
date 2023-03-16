@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{ $data->name }} {{ $data->surname }}
                </div>
                <div class="card-body">
                 @if (session()->has('message'))
                    <div class="alert alert-success">
                        {{ session()->get('message') }}
                    </div>
                @endif
                @role('ROLE_ADMIN')
                <form method="POST" action="{{ route('author.addbook') }}">
                    @csrf
                    <input type="hidden" name="author_id" value="{{$data->id}}"/>
                    <div class="row mb-3">
                     <label for="name" class="col-md-2 col-form-label text-md-end">Book</label>

                        <div class="col-md-10">
                            <select class="book-select form-control" name="books[]" multiple="multiple">
                                @foreach ($books as $book)
                                    <option value="{{$book->id}}">{{ $book->name }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                     <div class="row mb-0">
                        <div class="offset-md-2 col-md-10">
                            <button type="submit" class="btn btn-primary form-control">
                                Save
                            </button>
                        </div>
                    </div>
                </form>
                @endrole
            </div>
        </div>
 
            <div class="row" style="margin-top: 30px;">
                <div class="col-12 table-responsive">
                    <table class="table table-bordered book_datatable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>ISBN</th>
                                <th>Name</th>
                                <th>Detail</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th width="100px">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
 <script type="text/javascript">
        $(function () {
          var table = $('.book_datatable').DataTable({
              processing: true,
              serverSide: true,
              ajax: "{{ route('authorbook.datatable', $data->id) }}",
              order: [0, 'desc'],
              columns: [
                  {data: 'id', name: 'id'},
                  {data: 'isbn', name: 'isbn'},
                  {data: 'name', name: 'name'},
                  {data: 'detail', name: 'detail'},
                  {data: 'price', name: 'price'},
                  {data: 'quantity', name: 'quantity'},
                  {data: 'action', name: 'action', orderable: false, searchable: false},
              ]
          });
        });
      </script>
    <script>
    $(document).ready(function() {
        $('.book-select').select2();
    });
    </script>
@endsection