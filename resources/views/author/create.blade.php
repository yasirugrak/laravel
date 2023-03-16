@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @role('ROLE_ADMIN')
                <div class="card">
                    <div class="card-header">Add Author</div>
                    <div class="card-body">
                        @if (session()->has('message'))
                            <div class="alert alert-success">
                                {{ session()->get('message') }}
                            </div>
                        @endif
                        <form method="POST" action="{{ route('author.store') }}">
                            @csrf

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
                                <label for="surname" class="col-md-4 col-form-label text-md-end">Surname</label>

                                <div class="col-md-6">
                                    <input id="surname" type="text"
                                        class="form-control @error('surname') is-invalid @enderror" name="surname"
                                        value="{{ old('surname') }}" required autocomplete="surname" autofocus>
                                    @error('surname')
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
                        <table class="table table-bordered author_datatable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Surname</th>
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
    <script type="text/javascript">
        var table;
        $( document ).ready(function() {
            table = $('.author_datatable').DataTable({
              processing: true,
              serverSide: true,
              ajax: "{{ route('author.datatable') }}",
              order: [0, 'desc'],
              columns: [
                  {data: 'id', name: 'id'},
                  {data: 'name', name: 'name'},
                  {data: 'surname', name: 'surname'},
                  {data: 'action', name: 'action', orderable: false, searchable: false},
              ]
          });
        });
        function author_delete(url) {
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
