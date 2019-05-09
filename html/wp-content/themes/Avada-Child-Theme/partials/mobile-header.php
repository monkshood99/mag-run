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
                      <div class = 'user-avatar'  ng-if="$ctrl.MRS.userMeta.avatar" style = 'background-image:url({{$ctrl.MRS.userMeta.avatar}});'></div>
                      <span class="fa fa-user" ng-if="!$ctrl.MRS.userMeta.avatar"></span>
                      <span class = 'fa fa-pencil btn-edit'></span>
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

      <div class="modal-body text-center">

        <div class = 'container-fluid'>

          <div class=" row mb-1">
              <div class = 'col-10'>
                  <h1>Profile Avatar</h1>
                  <!-- <div class = 'h5-light'><small>Looking Good Out THere!</small></div> -->
              </div>
              <div class="col-2 text-right">
                  <button type="button" class="close btn-modal-close" data-dismiss="modal" aria-label="Close"><span class = 'fa fa-close'></span></button>
              </div>
              <hr/>
          </div>
          <div class = 'row'>
            <div class="col">
                <div class = 'profile-avatar mr-1 avatar-preview' data-toggle="modal" data-target="#modal-avatar" href="#modal-avatar" >
                  <img ng-if="$ctrl.MRS.userMeta.avatar || $ctrl.data.avatar" ngf-thumbnail="$ctrl.data.avatar || $ctrl.MRS.userMeta.avatar" ngf-size="{width: 100, height: 100 }" >
                  <span class="fa fa-user" ng-if="!$ctrl.MRS.userMeta.avatar && !$ctrl.data.avatar"></span>
                </div>


              <form class = 'my-2 py-2'>
                  <div class = 'form-group' >
                    <input type="file" ngf-select ng-model="$ctrl.data.avatar" name="avatar" class = 'form-control' accept=".jpg">
                </div>
                <div class = 'd-flex align-items-baseline justify-content-space-between' ng-show="$ctrl.submitting">
                    <div class = 'progress-bar'><div class =  'progress' style="width:{{  $ctrl.uploadProgress  }}%"></div></div>
                </div>
                <br clear = 'all'/>
                <button type = 'submit' class = 'mg-btn btn-xl btn-yellow ' ng-click="$ctrl.submit()" >
                  <span class = 'fa fa-spin fa-refresh' ng-show="$ctrl.submitting"></span>
                  <span  ng-show="!$ctrl.submitting">Update</span>
                </button>
              </form>
            </div>
          </div>
        </div><!-- // container --> 
      </div><!-- // body --> 
    </div><!-- // content -->
  </div><!-- // dialog --> 
</div><!-- // /modal --> 
                  


