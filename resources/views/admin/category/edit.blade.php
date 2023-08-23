@extends('admin.layout.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Category</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{route('categories.index')}}" class="btn btn-primary">Back</a>
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
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input value="{{$category->name}}" type="text" name="name" id="name" class="form-control" placeholder="Name">
                                <input value="{{$category->id}}" type="hidden" name="id" id="id" class="form-control">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">Slug</label>
                                <input value="{{$category->slug}}" type="text" readonly name="slug" id="slug" class="form-control" placeholder="Slug">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option {{ $category->status == '1' ? 'selected':'' }} value="1">Active</option>
                                    <option {{ $category->status == '0' ? 'selected':'' }} value="0">Block</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="image">Image</label>
                                <input type="hidden" name="image_id" id="image_id" class="form-control">
                                <div id="image" class="dropzone dz-clickable">
                                    <div class="dz-message needsclick">
                                        <br> Drop File Here or Click to Upload Fie <br><br>
                                    </div>
                                </div>
                            </div>
                            @if(!empty($category->image))
                                <img width="250" alt="" src="{{ asset('uploads/category/thumb/'.$category->image ) }}">
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="pb-5 pt-3">
                <button type="submit" id="createBtn" class="btn btn-primary">Update</button>
                <a href="#" class="btn btn-outline-dark ml-3">Cancel</a>
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
            url: '{{ route("categories.update") }}',
            type: 'post',
            data: element.serializeArray(),
            dataType: 'json',
            success: function(response) {
                if (response['status'] == true) {
                    $('#createBtn').prop('disabled', false);
                    window.location.href = "{{route('categories.index')}}";
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

    //  Image Uploading
    Dropzone.autoDiscover = false;
    const dropzone = $('#image').dropzone({

        //  Function for single image upload condition
        init: function() {
            this.on('addedfile', function(file) {
                if (this.files.length > 1) {
                    this.removeFile(this.files[0]);
                }
            });
        },

        url: '{{route("temp-image.create")}}',
        maxFiles: 1,
        addRemoveLinks: true,
        paramName: 'image',
        acceptedFiles: "image/gif, image/png, image/jpeg,",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(file, response) {
            $('#image_id').val(response['image_id']);
        }
    });
</script>


@endsection