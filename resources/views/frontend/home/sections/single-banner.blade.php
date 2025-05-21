<section id="wsus__single_banner" class="wsus__single_banner_2">
    <div class="container">
        <div class="row">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-2">
                @if ($homepage_secion_banner_two->banner_one->status == 1)
                <div id="wsus__single_banner_content1" class="wsus__single_banner_content">
                    <a href="{{$homepage_secion_banner_two->banner_one->banner_url}}">
                        <div class="wsus__single_banner_img">
                            <img class="img-fluid" src="{{ asset($homepage_secion_banner_two->banner_one->banner_image) }}" alt="">
                        </div>
                    </a>   
                </div>
                @endif
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-2">
                @if ($homepage_secion_banner_two->banner_two->status == 1)
                <div class="wsus__single_banner_content">
                    <a href="{{$homepage_secion_banner_two->banner_two->banner_url}}">
                        <div class="wsus__single_banner_img">
                            <img class="img-fluid" src="{{ asset($homepage_secion_banner_two->banner_two->banner_image) }}" alt="">
                        </div>
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
