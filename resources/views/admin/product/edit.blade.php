@extends('admin.layout.app')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Product</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{route('products.index')}}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <form action="" method="post" id="productForm" name="productForm">
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="title">Title</label>
                                        <input value="{{$product->title}}" type="text" name="title" id="title" class="form-control" placeholder="Title">
                                        <input value="{{$product->id}}" type="hidden" name="id" id="id">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="slug">Slug</label>
                                        <input value="{{$product->slug}}" type="text" readonly name="slug" id="slug" class="form-control" placeholder="Slug">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="description" cols="30" rows="10" class="summernote" placeholder="Description">{{$product->description}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Media</h2>
                            <div id="image" class="dropzone dz-clickable">
                                <input type="hidden" name="image_id" id="image_id" class="form-control">
                                <div class="dz-message needsclick">
                                    <br>Drop files here or click to upload.<br><br>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="temp_image">
                        @if(!empty($productImages))
                            @foreach($productImages as $productImage)
                            <div class="col-md-3" id="image-row-{{$productImage->id}}">
                                <div class="card">
                                    <img class="card-img-top" alt="..." src="{{ asset('uploads/product/thumb/'.$productImage->image ) }}">
                                    <div class="card-body">
                                        <a href="javascript:void(0)" onclick="deleteImage('{{$productImage->id}}')" class="btn btn-danger">Delete</a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @endif
                        <!-- Image card append with javascript -->
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Pricing</h2>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="price">Price</label>
                                        <input value="{{$product->price}}" type="text" name="price" id="price" class="form-control" placeholder="Price">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="compare_price">Compare at Price</label>
                                        <input value="{{$product->compare_price}}" type="text" name="compare_price" id="compare_price" class="form-control" placeholder="Compare Price">
                                        <p class="text-muted mt-3">
                                            To show a reduced price, move the product’s original price into Compare at price. Enter a lower value into Price.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Inventory</h2>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sku">SKU (Stock Keeping Unit)</label>
                                        <input value="{{$product->sku}}" type="text" name="sku" id="sku" class="form-control" placeholder="sku">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="barcode">Barcode</label>
                                        <input value="{{$product->barcode}}" type="text" name="barcode" id="barcode" class="form-control" placeholder="Barcode">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <div class="custom-control custom-checkbox">
                                            <input type="hidden" id="track_qty" name="track_qty" value="{{$product->track_qty}}" checked>
                                            <input class="custom-control-input" type="checkbox" id="track_qty" name="track_qty" value="Yes" checked>
                                            <p></p>
                                            <label for="track_qty" class="custom-control-label">Track Quantity</label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <input value="{{$product->qty}}" type="number" min="0" name="qty" id="qty" class="form-control" placeholder="Qty">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Product status</h2>
                            <div class="mb-3">
                                <select name="status" id="status" class="form-control">
                                    <option {{ $product->status == '1' ? 'selected':'' }} value="1">Active</option>
                                    <option {{ $product->status == '0' ? 'selected':'' }} value="0">Block</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h2 class="h4  mb-3">Product category</h2>
                            <div class="mb-3">
                                <label for="category">Category</label>
                                <select name="category" id="category" class="form-control">
                                    @if($categories->isNotEmpty())
                                    @foreach($categories as $category)
                                    <option {{ $product->category_id == "$category->id" ? 'selected':'' }} value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="category">Sub category</label>
                                <select name="sub_category" id="sub_category" class="form-control">
                                    <option value="">Select sub category</option>
                                    @if($sub_catogries->isNotEmpty())
                                    @foreach($sub_catogries as $sub_category)
                                    <option {{ $product->sub_category_id == "$sub_category->id" ? 'selected':'' }} value="{{$sub_category->id}}">{{$sub_category->name}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Product brand</h2>
                            <div class="mb-3">
                                <select name="brand" id="brand" class="form-control">
                                    @if($brands->isNotEmpty())
                                    @foreach($brands as $brand)
                                    <option {{ $product->brand_id == "$brand->id" ? 'selected':'' }} value="{{$brand->id}}">{{$brand->name}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Featured product</h2>
                            <div class="mb-3">
                                <select name="is_featured" id="is_featured" class="form-control">
                                    <option {{ $product->is_featured == 'No' ? 'selected':'' }} value="No">No</option>
                                    <option {{ $product->is_featured == 'Yes' ? 'selected':'' }} value="Yes">Yes</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pb-5 pt-3">
                <button id="createBtn" class="btn btn-primary">Update</button>
                <a href="{{route('products.index')}}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </form>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->

@endsection

@section('customJS')

<script type="text/javascript">
    $(function() {
        // Summernote
        $('.summernote').summernote({
            height: '300px'
        });
    });

    // Slug generation on behalf of name
    $('#title').change(function() {
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

    // sub_categories on selection of category
    $('#category').change(function() {
        var category_id = $(this).val();
        $.ajax({
            url: '{{ route("getSubCategory") }}',
            type: 'get',
            data: {
                category_id: category_id
            },
            dataType: 'json',
            success: function(response) {
                // console.log(response);
                if (response['status'] == true) {
                    $('#sub_category').find("option").not(":first").remove();
                    $.each(response["sub_categories"], function(key, item) {
                        $('#sub_category').append(`<option value = '${item.id}'>${item.name}</option>`);
                    });
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
        url: '{{route("productImage.update",$product->id)}}',
        maxFiles: 10,
        addRemoveLinks: true,
        paramName: 'image',
        acceptedFiles: "image/gif, image/png, image/jpeg,",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(file, response) {
            // $('#image_id').val(response['image_id']);
            var html = `
                <div class="col-md-3" id="image-row-${response.image_id}">
                    <div class="card">
                        <input type="hidden" name="image_array[]" value="${response.image_id}">
                        <img src="${response.image_path}" class="card-img-top" alt="...">
                        <div class="card-body">
                            <a href="javascript:void(0)" onclick="deleteImage(${response.image_id})" class="btn btn-danger">Delete</a>
                        </div>
                    </div>
                </div>
            `;
            $('#temp_image').append(html);
        },
        complete: function(file) {
            this.removeFile(file);
        }
    });

    // Delete card image
    function deleteImage(id) {
        if (confirm("Are you sure you want to delete this image")) {
            $.ajax({
                url: '{{ route("productImage.delete") }}',
                type: 'delete',
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    // console.log(response);
                    if (response['status'] == true) {
                        $("#image-row-" + id).remove();
                    }
                },
                error: function(jqXHR, exception) {
                    console.log("Something went wrong");
                }
            });  
        }      
    }

    // Validation and product store ajax function
    $('#productForm').submit(function(event) {
        event.preventDefault();
        var element = $(this);
        $('#createBtn').prop('disabled', true);
        $.ajax({
            url: '{{ route("products.update", $product->id) }}',
            type: 'put',
            data: element.serializeArray(),
            dataType: 'json',
            success: function(response) {
                if (response['status'] == true) {
                    $('#createBtn').prop('disabled', false);
                    window.location.href = "{{route('products.index')}}";
                    $('#title').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                    $('#slug').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                    $('#price').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                    $('#sku').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                    $('#track_qty').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                } else {
                    var errors = response['errors'];


                    if (errors['title']) {
                        $('#title').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['title']);
                    } else {
                        $('#title').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                    }

                    if (errors['slug']) {
                        $('#slug').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['slug']);
                    } else {
                        $('#slug').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                    }

                    if (errors['price']) {
                        $('#price').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['price']);
                    } else {
                        $('#price').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                    }

                    if (errors['sku']) {
                        $('#sku').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['sku']);
                    } else {
                        $('#sku').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                    }

                    if (errors['track_qty']) {
                        $('#track_qty').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['track_qty']);
                    } else {
                        $('#track_qty').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                    }
                }
            },
            error: function(jqXHR, exception) {
                console.log("Something went wrong");
            }
        })
    });
</script>

@endsection