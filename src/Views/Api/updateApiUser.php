<?php 

include __DIR__."/../../header.php";


?>
<style type="text/css">
    h6.titleBar, h5 {
        margin: 15px 0;
    }
    h5 {
        margin-bottom: 0;
        font-style: bold;
    }
    div.box {
        height: 200px;
        overflow-y: scroll;
        border: 1px solid #cacaca;
        background: #FAFAFA;
    }
    .elementBox {
        border: 1px solid #e0e0e0;
        padding: 10px;
        box-shadow: inset 0 1px 2px rgba(10, 10, 10, 0.1);
    }
    .error {
        color: red;
    }
</style>
<div class="small-12 large-12 columns">
    <nav aria-label="You are here:" role="navigation">
      <ul class="breadcrumbs"><br/>
        
        <li>
          <span class="show-for-sr">Current: </span>Update Api - <?php echo $update['text'][0]['name']; ?>
        </li>
      </ul>
  </div>
<div class="large-12 columns">

    
    <form action="/Api/postUpdateApiUser" method="post" id="adminUserForm" data-abide autocomplete="off" onsubmit = "return encryptValue();">
    <div class="large-9 small-6 columns">
          <h6 class="titleBar">Basic Information</h6>
           <div class="row">
            
            <div class="large-12 small-9 columns">
              <label> User Id             
                  <input type="text" class="user_id" placeholder="User_Id" name="user_id" autocomplete="false"value="<?php echo $update['text'][0]['user_id'];?>"   required readonly />
                  <small class="error hide" id="errorEmail"></small>
              </label>
            </div>
        </div> 
        <div class="row">
            <div class="large-12 small-9 columns input-wrapper">
              <label> Name              
                <input type="text"  name="name" value="<?php echo $update['text'][0]['name'];?>"  pattern="[a-zA-Z ]+" />
              </label>
              <small class="error hide">Name is required and must be a string.</small>
            </div>
        </div>    
        
        <div class="row">
            <div class="large-12 small-9 columns">
              <label> Mobile Number             
                  <input type="text" placeholder="Mobile Number" name="mob_number"  maxlength="10" value="<?php echo $update['text'][0]['mob_number'];?>"  required pattern="[0-9]+" />
              </label>
              <small class="error hide">Invalid entry</small>
            </div>
        </div>
        
        <div class="row">
            <div class="medium-12 small-9 columns callout alert hide" id="passmatcherror" style="margin-left: 1rem; margin-bottom: 10px;">
                <div></div>
            </div>
        </div>
        
        <?php if($organizations['responseType'] === '1') { ?>
<div class="row">
            <div class="large-12 small-9 columns">
                <label> Select organization </label>
                    <select name="organizations" id="organizations" required>
                         <option value="select" selected disabled>Select organization</option> 
                        <?php foreach($organizations['text'] as $value):?>
                            <option value="<?=$value['id'];?>" <?php if((isset($userOrganizations) && in_array($value['id'], $userOrganizations['text'][0]) !== false)) { ?> selected <?php } ?> /> <?=$value['name'];?></option> 
                        <?php endforeach;?>
                    </select>
                <small class="error hide">Invalid entry</small>
            </div>
        </div>


        <?php } ?>
        
            <input type="hidden" id="passsalt" name="passsalt" value="<?php 
                if(isset($update) && $update == 1) {
                    echo $_SESSION['salt_user_update']; 
                } else{
                    echo $_SESSION['salt_register'];         
                }
            ?>">
       <div class = "row">
                <div class="small-12 large-12 columns" style="margin-bottom: 10px;">
                    <label style="margin-bottom: 10px;">Select API(s) to allow access</label>
                    <?php foreach($api['text'] as $value):?>
                        <div class="columns large-4 small-12">
                            <input type="checkbox" id="<?=$value['id'];?>" name="apiname[<?=$value['id'];?>]"  value="<?=$value['id'];?>"<?php if((isset($ApiAccess) && in_array($value['id'], $ApiAccess))) { ?> checked<?php } ?> /><label for="<?=$value['id'];?>"> <?=$value['apiname'];?></label>
                        </div>
                    <?php endforeach;?>
                </div>
            </div>          
       
  </div>
        <div class="row">
            <div class="large-12 columns" style="padding-top:15px;">
                <input type="submit" class="button" name="save_user" value="Save User">
                <a href="/Api/getApiUser"><div class="secondary button" value="Cancel" style="display:inline-block;">Cancel</div></a>
            </div>
        </div>
    </form>
</div>
    <script type="text/javascript" src="<?php echo "/js/vendor/crypto.js"; ?>"></script>
    <script type="text/javascript" src="<?php echo "/js/vendor/aes-json-format.js"; ?>"></script>
<?php include __DIR__."/../../footer.php" ?>