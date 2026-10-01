<div id="bannerControls" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <?php
            $bannerpath = FCPATH.'dist/banner/';
            if (is_dir($bannerpath)) {
                $bannerfiles = glob($bannerpath . '*.png');
            
                $firstactive = true; // Flag variable to track the first item
            
                foreach ($bannerfiles as $index => $bannerfile) {
                    $bannerfilename = basename($bannerfile);
                    $activeClass = $firstactive ? ' active' : ''; // Add 'active' class for the first item
            
                    echo "<button type='button' data-bs-target='#carouselExampleIndicators' data-bs-slide-to='{$index}' class='{$activeClass}'></button>" . PHP_EOL;
            
                    $firstactive = false; // Set the flag to false after the first iteration
                }
            }
        ?>
    </div>
    <div class="carousel-inner">
        <?php
            $bannerpath = FCPATH.'dist/banner/';
            if (is_dir($bannerpath)) {
                $bannerfiles = glob($bannerpath.'*.png');
                
                $firstItem = true; // Flag variable to track the first item

                foreach ($bannerfiles as $bannerfile) {
                    $bannerfilename = basename($bannerfile);
                    $activeClass = $firstItem ? ' active' : ''; // Add 'active' class for the first item
                    echo "<div class='carousel-item{$activeClass}'><img src='".base_url()."dist/banner/".$bannerfilename."' class='d-block w-100' style='border-style: solid; border-width: 10px; border-color: #fff; border-radius: 25px;'></div>".PHP_EOL;
                    
                    $firstItem = false; // Set the flag to false after the first iteration
                }
            }
        ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#bannerControls" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#bannerControls" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>