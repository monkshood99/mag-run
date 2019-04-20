<?php 
    $fname = return_if( $user_meta, 'first_name');
    $city = return_if( $user_meta , 'mepr-address-city');
    $state = return_if( $user_meta , 'mepr-address-state');
    $lname = return_if( $user_meta, 'last_name');

?>
<div class = 'container  mb-1'>
    <div class = 'row'>
        <div class = 'col-10'>
            <div class=" profile-header d-flex pt-1">
                <div class = 'profile-avatar mr-1'><span class="fa fa-user"></span></div>
                <div>
                    <h1 class = 'mg-h4 mb-0 pb-0' style = 'margin-left:4px'><?= $fname . ' ' . $lname ;?></h1>
                    <!-- <?php if( $city || $state ){?>
                        <h5 class = mg-h5><i class="glyphicon fa-map-marker fas text-yellow"></i><?= $city;?><?= $city && $state ? ',' : '';?> <?= $state;?></h5>
                    <?php } ?> -->
                    <h5 class = "mg-h5 py-0 my-0 "><i class="glyphicon fa-map-marker fas text-yellow"></i> Boulder,Co</h5>
                </div>
            </div>
        </div>
        <div class = 'col-2'>
            <a class = 'profile-header__settings' href='/'><span class = 'fa fa-gear pull-right'></span></a>
        </div>
        <div class = 'col'>
            <hr class = 'sm'/>
        </div>
    </div>
</div>
