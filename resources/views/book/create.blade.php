@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @role('ROLE_ADMIN')
                <div class="card">
                    <div class="card-header">Add Book</div>
                    <div class="card-body">
                        @if (session()->has('message'))
                            <div class="alert alert-success">
                                {{ session()->get('message') }}
                            </div>
                        @endif
                        <form method="POST" action="{{ route('book.store') }}">
                            @csrf

                            <div class="row mb-3">
                                <label for="isbn" class="col-md-4 col-form-label text-md-end">ISBN</label>

                                <div class="col-md-6">
                                    <input id="isbn" type="text"
                                        class="form-control @error('isbn') is-invalid @enderror" name="isbn"
                                        value="{{ old('isbn') }}" required autocomplete="isbn" autofocus>
                                    @error('isbn')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end">Name</label>

                                <div class="col-md-6">
                                    <input id="name" type="text"
                                        class="form-control @error('name') is-invalid @enderror" name="name"
                                        value="{{ old('name') }}" required autocomplete="name" autofocus>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="detail" class="col-md-4 col-form-label text-md-end">Detail</label>

                                <div class="col-md-6">
                                    <input id="detail" type="text"
                                        class="form-control @error('detail') is-invalid @enderror" name="detail"
                                        value="{{ old('detail') }}" autocomplete="detail" autofocus>
                                    @error('detail')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <label for="price" class="col-md-4 col-form-label text-md-end">Price</label>

                                <div class="col-md-6">
                                    <input id="price" type="number"  step="any"
                                        class="form-control @error('price') is-invalid @enderror" name="price"
                                        value="{{ old('price') }}" required autocomplete="price" autofocus>
                                    @error('price')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <label for="quantity" class="col-md-4 col-form-label text-md-end">Quantity</label>

                                <div class="col-md-6">
                                    <input id="quantity" type="number"
                                        class="form-control @error('quantity') is-invalid @enderror" name="quantity"
                                        value="{{ old('quantity') }}" required autocomplete="quantity" autofocus>
                                    @error('quantity')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Save
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @endrole
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
    var table;
    $( document ).ready(function() {
        table = $('.book_datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('book.datatable') }}",
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
    function book_delete(url) {
        if(confirm("Are you sure you want to delete this?")){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                type: 'DELETE',
                success: function(result) {
                    if(result.status == 'success'){
                       table.draw();
                   }else{
                        alert('error');
                   }
                }
            });
        }
    }
</script>
@endsection