<?php $userStats = Atw_app::getUserStats();?>

<li mag-run-menu-component user-stats='<?= json_encode_attr( $userStats);?>' class = 'hidden'>
    <?php if( is_user_logged_in()){?>
    <form ng-submit="$ctrl.addRun()">
       <input type = 'number' ng-model="$ctrl.run_data.distance" name="distance" placeholder="Distance" ng-hide="$ctrl.success"/>
       <button type = 'submit' class = 'wpcf7-form-control wpcf7-submit'>
           <span ng-show="$ctrl.posting">Posting</span>
           <span ng-show="$ctrl.success">Posted!</span>
           <span ng-hide="$ctrl.success || $ctrl.posting ">Post It!</span>
        </button>
    </form>
    <ul>
        <li><a href="<?= Atw_app::get_var( 'link_account');?>">My Account</a></li>
    </ul>
    <?php }else{ ?>
        <ul>
            <li><a href="<?= Atw_app::get_var( 'link_login');?>">Login</a></li>
            <li><a href="<?= Atw_app::get_var( 'link_register');?>">Join Now</a></li>
        </ul>
    <?php } ?>
</li>
