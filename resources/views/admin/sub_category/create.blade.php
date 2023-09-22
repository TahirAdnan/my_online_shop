@extends('admin.layout.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Sub Category</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{route('subCategories.index')}}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <form action="" method="post" id="categoryForm" name="categoryForm">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="name">Category</label>
                                <select name="category_id" id="category_id" class="form-control">                                  
                                    @if(!empty($categories))
                                        @foreach($categories as $category)
                                            <option value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Name">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">Slug</label>
                                <input type="text" name="slug" id="slug" class="form-control" placeholder="Slug">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="1">Active</option>
                                    <option value="0">Block</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="showHome">Show Home</label>
                                <select name="showHome" id="showHome" class="form-control">
                                    <option value="No">No</option>
                                    <option value="Yes">Yes</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    <div class="pb-5 pt-3">
        <button type="submit" id="createBtn" class="btn btn-primary">Create</button>
        <a href="{{route('subCategories.index')}}" class="btn btn-outline-dark ml-3">Cancel</a>
    </div>
    </form>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection

@section('customJS')

<script type="text/javascript">
    // Validation and category store ajax function
    $('#categoryForm').submit(function(event) {
        event.preventDefault();
        var element = $(this);
        $('#createBtn').prop('disabled', true);
        $.ajax({
            url: '{{ route("subCategories.store") }}',
            type: 'post',
            data: element.serializeArray(),
            dataType: 'json',
            success: function(response) {
                if (response['status'] == true) {
                    $('#createBtn').prop('disabled', false);
                    window.location.href = "{{route('subCategories.index')}}";
                    $('#name').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                    $('#slug').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                } else {
                    var errors = response['errors'];
                    if (errors['name']) {
                        $('#name').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['name']);
                    } else {
                        $('#name').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                    }

                    if (errors['slug']) {
                        $('#slug').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['slug']);
                    } else {
                        $('#slug').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                    }
                }
            },
            error: function(jqXHR, exception) {
                console.log("Something went wrong");
            }
        })
    });

    // Slug generation on behalf of name
    $('#name').change(function() {
        var element = $(this);
        $('#createBtn').prop('disabled', true);
        $.ajax({
            url: '{{ route("getSlug") }}',
            type: 'get',
            data: {
                title: element.val()
            },
            dataType: 'json',
            success: function(response) {
                if (response['status'] == true) {
                    $('#createBtn').prop('disabled', false);
                    $('#slug').val(response['slug']);
                }
            },
            error: function(jqXHR, exception) {
                console.log("Something went wrong");
            }
        });
    });
</script>


@endsection