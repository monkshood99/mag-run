<div class = 'container'>
    <div class = 'row'>
        <div class = 'col-10'>
            <div class=" profile-header" style = 'display:flex'>
                <div class = 'profile-avatar'><span class="fa fa-user"></span></div>
                <div>
                    <h1 class = 'mg-h4 mb-0 pb-0'><?= return_if( $user_meta, 'first_name') . ' ' .return_if( $user_meta, 'last_name')  ;?></h1>
                    <?php if( ( $city = return_if( $user_meta , 'mepr-address-city') ) || ( $state = return_if( $user_meta , 'mepr-address-state')) ){?>
                    <?php  }?>
                    <h5 class = mg-h5><i class="glyphicon fa-map-marker fas text-yellow"></i><?= $city;?><?= $city && $state ? ',' : '';?> <?= $state;?></h5>
                </div>
            </div>
        </div>
        <div class = 'col-2'>
            <a class = 'profile-header__settings' href='/'><span class = 'fa fa-gear pull-right'></span></a>
        </div>
        <div class = 'col'>
            <hr/>
        </div>
    </div>
</div>
