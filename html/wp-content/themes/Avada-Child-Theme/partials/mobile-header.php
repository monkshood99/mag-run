<?php 
    $fname = return_if( $user_meta, 'first_name');
    $city = return_if( $user_meta , 'mepr-address-city');
    $state = return_if( $user_meta , 'mepr-address-state');
    $lname = return_if( $user_meta, 'last_name');
    $avatar = Atw_app::getUserAvatar( $user_meta ); 

?>
<div class = 'container  mb-1'>
    <div class = 'row'>
        <div class = 'col-10'>
            <div class=" profile-header d-flex pt-1">
                <a class = 'profile-avatar mr-1' data-toggle="modal" data-target="#modal-avatar" href="#modal-avatar" >
                    <?php if( $avatar ) {?>
                      <div class = 'user-avatar' style = 'background-image:url(<?= $avatar;?>);'></div>
                    <?php } else {?>
                      <span class="fa fa-user"></span>
                    <?php } ?>
                </a>
                <div>
                    <h1 class = 'mg-h4 mb-0 pb-0' style = 'margin-left:4px'><?= $fname . ' ' . $lname ;?></h1>
                    <?php if( $city || $state ){?>
                        <h5 class = mg-h5><i class="glyphicon fa-map-marker fas text-yellow"></i><?= $city;?><?= $city && $state ? ',' : '';?> <?= $state;?></h5>
                    <?php } ?>
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

 <div class="modal"  id="modal-avatar" tabindex="-1" role="dialog" mg-avatar-upload user-id="<?= $current_user->ID;?>" >
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Change Your Avatar</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"><span class = 'icon-close'></span></span>
          </button>
        </div>
        <div class="modal-body">
          <form class = ' py-5 pt-5 pb-5'>
              <div class = 'form-group' >
                <input type="file" ngf-select ng-model="$ctrl.data.avatar" name="avatar" class = 'form-control' accept=".jpg">
                <img ngf-thumbnail="$ctrl.data.avatar" ngf-size="{width: 100, height: 100 }" >
            </div>
            <button type = 'submit' class = 'btn mg-btn ' ng-click="$ctrl.submit()" >Update</button>
            <div class = 'd-flex align-items-baseline justify-content-space-between' ng-show="$ctrl.submitting">
                <div class = 'progress-bar'><div class =  'progress' style="width:{{  $ctrl.uploadProgress  }}%"></div></div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>




