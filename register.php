<?php
include 'templates/head.php';
?>
<div class="container-narrow">
  <!-- Main hero unit for a primary marketing message or call to action -->
  <form class="form-horizontal well" id="register" method="post" action="register_post.php">
    <fieldset>
      <legend>Registration Form</legend>

      <!-- First Name -->
      <div class="control-group">
        <label class="control-label" for="register-first-name">First Name</label>
        <div class="controls">
          <input type="text" class="input-large" id="register-first-name" name="register-first-name">
        </div>
      </div>

      <!-- Last Name -->
      <div class="control-group">
        <label class="control-label" for="register-last-name">Last Name</label>
        <div class="controls">
          <input type="text" class="input-large" id="register-last-name" name="register-last-name">
        </div>
      </div>

      <!-- Email Address -->
      <div class="control-group">
        <label class="control-label" for="register-email">Email Address</label>
        <div class="controls">
          <input type="text" class="input-xlarge" id="register-email" name="register-email">
        </div>
      </div>

      <!--  Password -->
      <div class="control-group">
        <label class="control-label" for="register-password">Password</label>
        <div class="controls">
          <input type="password" class="input-medium" id="register-password" name="register-password"
          pattern=".{8,}" title="Password must be atleast 8 characters long">
        </div>
      </div>

      <!--  Repeat Password -->
      <div class="control-group">
        <label class="control-label" for="register-repeat-password">Repeat Password</label>
        <div class="controls">
          <input type="password" class="input-medium" id="register-repeat-password" name="register-repeat-password">
        </div>
      </div>

      <!-- Driver's License ID -->
      <div class="control-group">
        <label class="control-label" for="register-drivers-license-id">Driver's License ID</label>
        <div class="controls">
          <input type="text" class="input-xlarge" id="register-drivers-license-id" name="register-drivers-license-id" pattern="^[A-Za-z0-9]{0,15}$"
          title="Driver's license ID must be up to 15 characters long and contain only letters and numbers">
        </div>
      </div>

      <!--  Gender -->
      <div class="control-group">
        <label class="control-label">Gender</label>
        <div class="controls">
          <label class="radio">
            <input type="radio" name="register-gender" value="1" id="register-male"> Male
          </label>
          <label class="radio">
            <input type="radio" name="register-gender" value="0" id="register-female"> Female
          </label>
          <span class="help-inline" style="display:none;">Please select your gender</span>
        </div>
      </div>

      <!-- Submit button  -->

      <!--  Form Actions -->
      <div class="form-actions">
        <button type="submit" class="btn btn-primary" name="submit"> Submit</button>
        <button type="reset" class="btn">Cancel</button>
      </div>
    </fieldset>
  </form>
</div>
<!-- Load JS in the end for faster page loading -->
<script src="js/register.js"></script>
<?php include "templates/footer.php" ?>