<?php $userStats = Atw_app::getUserStats();?>
<li mag-run-menu-component user-stats='<?= json_encode_attr( $userStats);?>' >
    <?php if( is_user_logged_in()){?>

    <form ng-submit="$ctrl.addRun()">
        <div class = 'btn-with-input'>
            <input type = 'number' ng-model="$ctrl.run_data.distance" name="distance" placeholder="Distance" ng-hide="$ctrl.MRS.success"/>
            <button type = 'button' ng-click="show_submit = true" ng-hide="show_submit">
                Post My Run
            </button>
            <button type = 'submit' class = 'btn-submit hidden' ng-show="show_submit">
                <span ng-show="$ctrl.MRS.posting">Posting</span>
                <span ng-show="$ctrl.MRS.success">Posted!</span>
                <span ng-hide="$ctrl.MRS.success || $ctrl.MRS.posting ">Post It!</span>
            </button>
        </div>
    </form>
    <ul>
        <li><a href="<?= Atw_app::get_var( 'link_account');?>">My Account</a></li>
    </ul>
    <?php }else{ ?>
        <ul>
            <li><a href="<?= Atw_app::get_var( 'link_login');?>">Login</a></li>
            <li><a href="<?= Atw_app::get_var( 'link_register');?>">Join Now</a></li>
        </ul>
    <?php    } ?>
</li>
