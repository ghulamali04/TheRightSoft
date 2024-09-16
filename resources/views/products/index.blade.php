@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Products</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{url('')}}">Home</a></li>
                    <li class="breadcrumb-item"><a href="javascript:;">Products</a></li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="card">
        <div class="card-header w-100 d-flex justify-content-between">
        <h3 class="card-title">Products</h3>
        <button type="button" id="add-new-product" class="btn btn-sm btn-primary ml-auto">Add New</button>
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

<form method="POST" id="FormAddProduct" enctype="multipart/form-data">@csrf
    <div class="modal" id="modalAddProduct" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Add New Product</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">Product Name</label>
                    <input type="text" required class="form-control" name="name" placeholder="Product Name">
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">Categories</label>
                    <select class="form-contorl select2" name="categories[]" multiple>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">Images</label>
                    <input type="file" name="images[]" class="form-control" accept=".jpg,.jpeg,.png,.webp" multiple>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" name="description" placeholder="Description" required></textarea>
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


  <form method="POST" id="FormEditProduct" enctype="multipart/form-data">@csrf
    <input type="hidden" name="_method" value="PUT">
    <input type="hidden" name="id" value="">
    <div class="modal" id="modalEditProduct" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Edit Product</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">Product Name</label>
                    <input type="text" required class="form-control" name="name" placeholder="Product Name">
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">Categories</label>
                    <select class="form-contorl select2" name="categories[]" multiple>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">Images</label>
                    <input type="file" name="images[]" class="form-control" accept=".jpg,.jpeg,.png,.webp" multiple>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" name="description" placeholder="Description" required></textarea>
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
        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: 'Select Categories'
        })
        const table = $("#example1").DataTable({
            "paging": true,
            "ordering": true,
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            processing: true,
            serverSide: true,
            ajax: '{{ route('products.data') }}',
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
            const c = confirm("Are you really want to delete this product?")
            if(c) {
                $.ajax({
                    type: "POST",
                    url: "{{url('products')}}/"+dataId,
                    data: {
                        _token: "{{ csrf_token() }}",
                        _method: "DELETE"
                    }
                }).done(function (response) {
                    table.ajax.reload(null, false)
                })
            }
        })
        $(document).on('click', '.edit-btn', function () {
            const dataId = $(this).attr('data-id')
            $.ajax({
                type: "GET",
                url: "{{url('products')}}/"+dataId
            }).done(function (response) {
                $('#FormEditProduct input[name=id]').val(response.product.id)
                $('#FormEditProduct input[name=name]').val(response.product.name)
                $('#FormEditProduct textarea[name=description]').val(response.product.description)
                const categoriesId = []
                response.categories.forEach(category => {
                    categoriesId.push(category.pivot.category_id)
                })
                $('#FormEditProduct .select2').val(categoriesId).trigger('change')
                $('#modalEditProduct').modal('show')
            })
        })
        $(document).on('click', '#add-new-product', function () {
            $('#modalAddProduct').modal('show')
        })
        $('#FormAddProduct').on('submit', function (e) {
            e.preventDefault()
            e.stopImmediatePropagation()
            const form = document.getElementById('FormAddProduct')
            const formData = new FormData(form)
            $.ajax({
                type: "POST",
                url: '{{url('products')}}',
                data: formData,
                processData: false,
                contentType: false,
                cache: false
            }).done(function (response) {
                table.ajax.reload(null, false)
                $('#modalAddProduct').modal('hide')
            })
        })
        $('#FormEditProduct').on('submit', function (e) {
            e.preventDefault()
            e.stopImmediatePropagation()
            const form = document.getElementById('FormEditProduct')
            const formData = new FormData(form)
            const dataId = $('#FormEditProduct input[name=id]').val()
            $.ajax({
                type: "POST",
                url: '{{url('products')}}/'+dataId,
                data: formData,
                processData: false,
                contentType: false,
                cache: false
            }).done(function (response) {
                table.ajax.reload(null, false)
                $('#modalEditProduct').modal('hide')
            })
        })
    }, 2000);
</script>
@stop
