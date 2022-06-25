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
<div class="large-12 columns">
   
    <h5>Register User</h5>
    
    <form action="/Api/postNewApiUser" method="post" id="adminUserForm" data-abide autocomplete="off" onsubmit = "return encryptValue();">
    <div class="large-9 small-6 columns">
          <h6 class="titleBar">Basic Information</h6>
        <div class="row">
            <div class="large-12 small-9 columns input-wrapper">
              <label> Name              
                <input type="text" placeholder="Name" name="name"  required pattern="[a-zA-Z ]+" />
              </label>
              <small class="error hide">Name is required and must be a string.</small>
            </div>
        </div>    
        
        <div class="row">
            <div class="large-12 small-9 columns">
              <label> Mobile Number             
                  <input type="text" placeholder="Mobile Number" name="mobile_number"  maxlength="10" required pattern="[0-9]+" />
              </label>
              <small class="error hide">Invalid entry</small>
            </div>
        </div>
        <div class="row">
            <div class="large-12 small-9 columns">
              <label> Email             
                  <input type="email" class="email" placeholder="Email" name="email" autocomplete="false"  required />
                  <small class="error hide" id="errorEmail"></small>
              </label>
            </div>
        </div>      
            
        <div class="row">
            <div class="large-12 small-9 columns">
              <label> User Id
                <input type="text" class="user" placeholder="User Id" name="user_id" autocomplete="false"  required />
              </label>
              <small class="error hide" id="errorId">Invalid entry</small>
            </div>
        </div>
        
        <div class="row">
            <div class="large-12 small-9 columns">
              <label> Password              
                  <input type="password" placeholder="Password" name="password" id="user_password" required />
              </label>
              
              <small class="error hide">Invalid entry</small>
            </div>
        </div>
        <div class="row">
            <div class="medium-12 small-9 columns callout alert hide" id="passerror" style="margin-left: 1rem; margin-bottom: 10px;">
                <div></div>
            </div>
        </div>
        <div class="row">
            <div class="large-12 small-9 columns">
              <label> Confirm Password              
                  <input type="password" placeholder="Confirm Password" name="confirm_password" id="confirm_password" required data-equalto="user_password" disabled />
              </label>
              <small class="error hide">Invalid entry</small>
            </div>
        </div>
        <div class="row">
            <div class="medium-12 small-9 columns callout alert hide" id="passmatcherror" style="margin-left: 1rem; margin-bottom: 10px;">
                <div></div>
            </div>
        </div>
        
        
        <div class="row">
            <div class="large-12 small-9 columns">
                <label> Select organization </label>
                    <select name="organizations" id="organizations" required>
                         <option value="0" >Select organization</option> 
                        <?php foreach($organizations['text'] as $value):?>
                            <option value="<?=$value['id'];?>" <?php if($organizations['text'][0] === $value['id']) { ?> selected <?php } ?> > <?=$value['name'];?></option> 
                        <?php endforeach;?>
                        
                    </select>
                <small class="error hide">Invalid entry</small>
            </div>
        </div>
        
        
            <input type="hidden" id="passsalt" name="passsalt" value="<?php 
                if(isset($update) && $update == 1) {
                    echo $_SESSION['salt_user_update']; 
                } else{
                    echo $_SESSION['salt_register'];         
                }
            ?>">
       <div class="small-12 large-12 columns" style="margin-bottom: 10px;">
          <label>Select API</label>
        </div>
      <div class="small-12 large-12 columns" style="max-height: 500px;overflow-y: scroll; padding: 0;">
        <?php
          $html = '';
          $i=1;
          if($userapi['responseType'] === '1'){
            foreach($userapi['text'] as $record){
              $html .= '<div class="columns large-4 small-12"><input type="checkbox" id="'.$record["apiname"].'" name="apiname[]" value="'.$record['id'].'">
              <label for="'.$record["apiname"].'">'.$record["apiname"].'</label></div>';
              $i++;
            }
          }
          echo $html;
        ?>
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