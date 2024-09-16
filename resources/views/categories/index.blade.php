@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Categories</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('')}}">Home</a></li>
            <li class="breadcrumb-item"><a href="javascript:;">Categories</a></li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="card">
        <div class="card-header w-100 d-flex justify-content-between">
        <h3 class="card-title">Categories</h3>
        <button type="button" id="add-new-category" class="btn btn-sm btn-primary ml-auto">Add New</button>
        </div>
        <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Action</th>
                </thead>
            </table>
        </div>
    </div>
  </section>
  <!-- /.content -->


  <form method="POST" id="FormAddCategory">@csrf
    <div class="modal" id="modalAddCategory" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Add New Category</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                    <label class="form-label">Category Name</label>
                    <input type="text" required class="form-control" name="name" placeholder="Category Name">
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
          </div>
        </div>
      </div>
  </form>


  <form method="POST" id="FormEditCategory">@csrf
    <input type="hidden" name="_method" value="PUT">
    <input type='hidden' name="id" value="">
    <div class="modal" id="modalEditCategory" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Edit Category</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                    <label class="form-label">Category Name</label>
                    <input type="text" required class="form-control" name="name" placeholder="Category Name">
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
          </div>
        </div>
      </div>
  </form>
@stop

@section('javascript')
<script>
    setTimeout(() => {
        const table = $("#example1").DataTable({
            "paging": true,
            "ordering": true,
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            processing: true,
            serverSide: true,
            ajax: '{{ route('categories.data') }}',
            "columns": [
                { "data": "id" },
                { "data": "name" },
                {
                    "data": null,
                    "defaultContent": '',
                    "orderable": false,
                    "searchable": false,
                    "render": function (data, type, row) {
                        return `
                            <button class="btn btn-warning btn-sm edit-btn" data-id="${row.id}">Edit</button>
                            <button class="btn btn-danger btn-sm delete-btn" data-id="${row.id}">Delete</button>
                        `;
                    }
                }
            ],
        });
        $(document).on('click', '.delete-btn', function () {
            const dataId = $(this).attr('data-id')
            const c = confirm("Are you really want to delete this category?")
            if(c) {
                $.ajax({
                    type: "POST",
                    url: '{{ url('categories') }}/' + dataId,
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'DELETE'
                    }
                }).done(function (response) {
                    table.ajax.reload(null, false);
                })
            }
        })
        $(document).on('click', "#add-new-category", function () {
            $("#modalAddCategory").modal('show')
        })
        $(document).on('click', '.edit-btn', function () {
            const dataId = $(this).attr('data-id')
            $.ajax({
                type: "GET",
                url: "{{ url('categories') }}/"+dataId
            }).done(function (response) {
                $('#FormEditCategory input[name=name]').val(response.name)
                $('#FormEditCategory input[name=id]').val(response.id)
                $('#modalEditCategory').modal('show')
            })
        })
        $(document).on('submit', '#FormEditCategory', function (e) {
            e.preventDefault()
            e.stopImmediatePropagation()
            const form = document.getElementById('FormEditCategory')
            const formData = new FormData(form)
            const dataId = $('#FormEditCategory input[name=id]').val()
            $.ajax({
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                url: '{{ url('categories') }}/' + dataId
            }).done(function (response) {
                table.ajax.reload(null, false);
                $("#modalEditCategory").modal('hide')
            })
        })
        $(document).on('submit', '#FormAddCategory', function (e) {
            e.preventDefault()
            e.stopImmediatePropagation()
            const form = document.getElementById('FormAddCategory');
            const formData = new FormData(form);
            $.ajax({
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                url: '{{ url('categories') }}'
            }).done(function (response) {
                if(response) {
                    table.ajax.reload(null, false);
                    $("#modalAddCategory").modal('hide')
                }
            })
        })
    }, 2000);
</script>
@stop
