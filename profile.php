<?php
include "templates/head.php";
include "functions/user.php";
?>

<?php if (user\user_logged_in()): ?>
    <div class="container-narrow">
        <div id="profile-container" class="well">
            <h2>My Profile</h2>
            <div class="row-fluid">
                <div class="span12">
                    <div id="profile-loading-status">
                        <img id="profile-loader" class="loader" src="/img/ajaxloader.gif">
                        <em id="profile-msg" style="display: none;">Loading profile data...</em>
                    </div>
                    <table class="table" id="profile-info">
                        <!-- Profile data will be loaded here via AJAX -->
                    </table>

                    <div class="form-actions">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#editProfileModal">
                            <i class="icon-edit icon-white"></i> Edit Profile
                        </button>
                        <button class="btn" data-toggle="modal" data-target="#changePasswordModal">
                            <i class="icon-lock"></i> Change Password
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div id="editProfileModal" class="modal hide fade" tabindex="-1" role="dialog">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h3>Edit Profile</h3>
        </div>
        <div class="modal-body">
            <div id="profile-edit-loading-status">
                <img id="profile-edit-loader" class="loader" src="/img/ajaxloader.gif">
                <em id="profile-edit-msg" style="display: none;">Loading edit form...</em>
            </div>
            <form id="profile-form" class="form-horizontal">
                <div class="row-fluid">
                    <div class="span12" id="profile-edit-form">
                        <!-- Edit form will be loaded here via AJAX -->
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary" form="profile-form">Save Changes</button>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div id="changePasswordModal" class="modal hide fade" tabindex="-1" role="dialog">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h3>Change Password</h3>
        </div>
        <form id="change-password-form" class="form-horizontal">
            <div class="modal-body">
                <div class="control-group">
                    <label class="control-label">Current Password</label>
                    <div class="controls">
                        <input type="password" class="input-xlarge" name="current_password" required>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">New Password</label>
                    <div class="controls">
                        <input type="password" class="input-xlarge" name="new_password" pattern=".{8,}"
                            title="Password must be atleast 8 characters long" required>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Confirm New Password</label>
                    <div class="controls">
                        <input type="password" class="input-xlarge" name="confirm_password" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Change Password</button>
            </div>
        </form>
    </div>

    <!-- Profile Info Template -->
    <script type="text/template" id="profile-info-template">
                        <tr>
                            <th>First Name:</th>
                            <td id="profile-first-name"><%= first_name %></td>
                        </tr>
                        <tr>
                            <th>Last Name:</th>
                            <td id="profile-last-name"><%= last_name %></td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td id="profile-email"><%= email_address %></td>
                        </tr>
                        <tr>
                            <th>Gender:</th>
                            <td id="profile-gender"><%= gender == 1 ? 'Male' : 'Female' %></td>
                        </tr>
                        <tr>
                            <th>Driver's License ID:</th>
                            <td id="profile-license"><%= drivers_license_id %></td>
                        </tr>
                    </script>

    <!-- Profile Edit Form Template -->
    <script type="text/template" id="profile-edit-template">
                        <div class="control-group">
                            <label class="control-label">First Name</label>
                            <div class="controls">
                                <span class="input-xlarge uneditable-input" id="edit-first-name"><%= first_name %></span>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">Last Name</label>
                            <div class="controls">
                                <span class="input-xlarge uneditable-input" id="edit-last-name"><%= last_name %></span>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">Email</label>
                            <div class="controls">
                                <span class="input-xlarge uneditable-input" id="edit-email"><%= email_address %></span>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">Gender</label>
                            <div class="controls">
                                <span class="input-xlarge uneditable-input" id="edit-gender"><%= gender == 1 ? 'Male' : 'Female' %></span>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">Driver's License ID</label>
                            <div class="controls">
                                <input type="text" class="input-xlarge" name="drivers_license_id" id="edit-license" value="<%= drivers_license_id %>" pattern="^[A-Za-z0-9]{0,15}$" title="Driver's license ID must be up to 15 characters long and contain only letters and numbers">
                            </div>
                        </div>
                    </script>

    <script src="js/lib/underscore.min.js"></script>
    <script src="js/profile.js"></script>
<?php else: ?>
    <div class="well ds-component ds-hover container-narrow" data-componentid="well1">
        <div class="ds-component ds-hover" data-componentid="content2">
            <?php functions\html_respond('Log In Required', 'Please register or log in to access this part of the website'); ?>
        </div>
    </div>
<?php endif; ?>

<?php include "templates/footer.php" ?>