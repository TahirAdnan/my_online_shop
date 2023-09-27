@extends('front.layout.app')

@section('content')

<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="#">Home</a></li>
                <li class="breadcrumb-item active">Shop</li>
            </ol>
        </div>
    </div>
</section>

<section class="section-6 pt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-3 sidebar">
                <div class="sub-title">
                    <h2>Categories</h3>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="accordion accordion-flush" id="accordionExample">
                            @if($categories->isNotEmpty())
                            @foreach($categories as $key => $category)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne-{{$key}}">
                                    <button onclick="window.location=`{{ route('front.shop',[$category->slug]) }}`" class="accordion-button collapsed {{($category->id == $categoryId) ? 'text-primary' : ''}}" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne-{{$key}}" aria-expanded="false" aria-controls="collapseOne-{{$key}}">
                                        {{$category->name}}
                                    </button>
                                </h2>
                                @if($category->sub_categories->isNotEmpty())
                                <div id="collapseOne-{{$key}}" class="accordion-collapse collapse {{($category->id == $categoryId) ? 'show' : ''}}" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="navbar-nav">
                                            @foreach($category->sub_categories as $sub_category)
                                            <a href="{{ route('front.shop', [$category->slug, $sub_category->slug]) }}" class="nav-item nav-link {{($sub_category->id == $subCategoryId) ? 'text-primary' : ''}}">{{$sub_category->name}}</a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                            @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <div class="sub-title mt-5">
                    <h2>Brand</h3>
                </div>
                @if($brands->isNotEmpty())
                @foreach($brands as $key => $brand)
                <div class="card">
                    <div class="card-body">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                            <label class="form-check-label" for="flexCheckDefault">
                                {{$brand->name}}
                            </label>
                        </div>
                    </div>
                </div>
                @endforeach
                @endif


                <div class="sub-title mt-5">
                    <h2>Price</h3>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                            <label class="form-check-label" for="flexCheckDefault">
                                $0-$100
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked">
                            <label class="form-check-label" for="flexCheckChecked">
                                $100-$200
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked">
                            <label class="form-check-label" for="flexCheckChecked">
                                $200-$500
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked">
                            <label class="form-check-label" for="flexCheckChecked">
                                $500+
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="row pb-3">
                    <div class="col-12 pb-1">
                        <div class="d-flex align-items-center justify-content-end mb-4">
                            <div class="ml-2">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-light dropdown-toggle" data-bs-toggle="dropdown">Sorting</button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="#">Latest</a>
                                        <a class="dropdown-item" href="#">Price High</a>
                                        <a class="dropdown-item" href="#">Price Low</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($products->isNotEmpty())
                    @foreach($products as $product)
                    <div class="col-md-4">
                        <div class="card product-card">
                            <div class="product-image position-relative">
                                <a href="" class="product-img">
                                    @if($product->product_images->isNotEmpty())
                                    <img class="card-img-top" src="{{ asset('uploads/product/thumb/'.$product->product_images->first()->image) }}" alt="">
                                    @endif
                                </a>
                                <a class="whishlist" href="222"><i class="far fa-heart"></i></a>

                                <div class="product-action">
                                    <a class="btn btn-dark" href="#">
                                        <i class="fa fa-shopping-cart"></i> Add To Cart
                                    </a>
                                </div>
                            </div>
                            <div class="card-body text-center mt-3">
                                <a class="h6 link" href="product.php">{{ $product->title }}</a>
                                <div class="price mt-2">
                                    <span class="h5"><strong>${{ $product->compare_price }}</strong></span>
                                    <span class="h6 text-underline"><del>${{ $product->price }}</del></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @else
                        <div class="accordion-item text-center">Product Not Found</div>
                    @endif
                    <div class="col-md-12 pt-5">
                        {{ $products->links() }}
                        <!-- <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-end">
                                
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                                </li>
                                <li class="page-item"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#">Next</a>
                                </li>
                            </ul>
                        </nav> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection