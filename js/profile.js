// Profile page functionality
$(document).ready(function () {
    var profileController = new ProfileController();
});

// Profile Controller
function ProfileController() {
    var _this = this;

    // Main profile elements
    this.loader = $('#profile-loader');
    this.msg = $('#profile-msg');
    this.profileInfo = $('#profile-info');

    // Edit profile elements
    this.editForm = $('#profile-form');
    this.editLoader = $('#profile-edit-loader');
    this.editMsg = $('#profile-edit-msg');
    this.editModal = $('#editProfileModal');

    // Password change elements
    this.passwordForm = $('#change-password-form');
    this.currentPassword = $('input[name="current_password"]');
    this.newPassword = $('input[name="new_password"]');
    this.confirmPassword = $('input[name="confirm_password"]');

    // Load profile data
    this.loadProfileData = function () {
        this.loader.show();
        this.msg.hide();
        this.profileInfo.hide();

        $.ajax({
            url: 'profile_post.php',
            type: 'GET',
            data: {
                'method': 'get_profile',
                'data': JSON.stringify({})
            },
            success: this.handleProfileResponse.bind(this),
            error: this.handleError.bind(this)
        });
    };

    // Initialize edit profile form
    this.initializeEditForm = function () {
        this.editForm.on('submit', function (e) {
            e.preventDefault();
            _this.submitEditForm();
        });

        $('#edit-license').on('blur', function () {
            var $this = $(this);
            $this.val($.trim($this.val()));
        });

        this.editModal.on('show.bs.modal', function () {
            _this.loadEditForm();
        });
    };

    // Load edit form data
    this.loadEditForm = function () {
        this.editLoader.show();
        this.editMsg.hide();
        $('#profile-edit-form').hide();

        $.ajax({
            url: 'profile_post.php',
            type: 'GET',
            data: {
                'method': 'get_profile',
                'data': JSON.stringify({})
            },
            success: this.handleEditFormResponse.bind(this),
            error: this.handleEditError.bind(this)
        });
    };

    // Submit edit form
    this.submitEditForm = function () {
        this.editLoader.show();
        this.editMsg.hide();

        $.ajax({
            url: 'profile_post.php',
            type: 'POST',
            data: {
                'method': 'update_profile',
                'data': JSON.stringify(Object.fromEntries(new FormData(this.editForm[0])))
            },
            success: this.handleEditSubmitResponse.bind(this),
            error: this.handleEditError.bind(this)
        });
    };

    // Handle successful profile data response
    this.handleProfileResponse = function (response) {
        var data = JSON.parse(response);
        var _this = this;

        if (data.status === 'OK') {
            this.loader.fadeOut(500, function () {
                var template = _.template($('#profile-info-template').html());
                _this.profileInfo.html(template(data.user)).fadeIn(500);
            });
        } else {
            this.handleError(null, data.msg || 'Failed to load profile data');
        }
    };

    // Handle edit form load response
    this.handleEditFormResponse = function (response) {
        var data = JSON.parse(response);
        var _this = this;

        if (data.status === 'OK') {
            this.editLoader.fadeOut(500, function () {
                var template = _.template($('#profile-edit-template').html());
                $('#profile-edit-form').html(template(data.user)).fadeIn(500);
            });
        } else {
            this.handleEditError(null, data.msg || 'Failed to load edit form');
        }
    };

    // Handle edit form submit response
    this.handleEditSubmitResponse = function (response) {
        var data = JSON.parse(response);

        if (data.status === 'OK') {
            this.editModal.modal('hide');
            this.loadProfileData();
            this.showSuccessMessage('Profile updated successfully');
        } else {
            this.handleEditError(null, data.msg || 'Failed to update profile');
        }
    };

    // Handle main profile errors
    this.handleError = function (xhr, status) {
        var _this = this;
        this.loader.fadeOut(500, function () {
            _this.msg.html("<em>" + (xhr ? xhr.status + ": " + xhr.statusText : status) + "</em>").fadeIn(500);
        });
    };

    // Handle edit form errors
    this.handleEditError = function (xhr, status) {
        var _this = this;
        this.editLoader.fadeOut(500, function () {
            _this.editMsg.html("<em>" + (xhr ? xhr.status + ": " + xhr.statusText : status) + "</em>").fadeIn(500);
        });
    };

    // Show success message
    this.showSuccessMessage = function (message) {
        var alert = $('<div class="alert alert-success">' + message + '</div>');
        this.profileInfo.before(alert);
        setTimeout(function () {
            alert.fadeOut(500, function () {
                $(this).remove();
            });
        }, 3000);
    };

    // Initialize password change form
    this.initializePasswordForm = function () {
        this.passwordForm.on('submit', function (e) {
            e.preventDefault();
            _this.submitPasswordChange();
        });

        // Add blur event handlers for validation
        this.currentPassword.on('blur', function () {
            _this.verifyRequiredField($(this));
        });

        this.newPassword.on('blur', function () {
            _this.verifyRequiredField($(this));
            _this.verifyPasswordsMatch(_this.newPassword, _this.confirmPassword);
        });

        this.confirmPassword.on('blur', function () {
            _this.verifyRequiredField($(this));
            _this.verifyPasswordsMatch(_this.newPassword, _this.confirmPassword);
        });
    };

    // Submit password change form
    this.submitPasswordChange = function () {
        var errors = false;

        // Validate all fields
        if (!this.verifyRequiredField(this.currentPassword)) errors = true;
        if (!this.verifyRequiredField(this.newPassword)) errors = true;
        if (!this.verifyRequiredField(this.confirmPassword)) errors = true;
        if (!this.verifyPasswordsMatch(this.newPassword, this.confirmPassword)) errors = true;

        if (!errors) {
            $.ajax({
                url: 'profile_post.php',
                type: 'POST',
                data: {
                    'method': 'change_password',
                    'data': JSON.stringify(Object.fromEntries(new FormData(this.passwordForm[0])))
                },
                success: this.handlePasswordChangeResponse.bind(this),
                error: this.handleError.bind(this)
            });
        }
    };

    // Handle password change response
    this.handlePasswordChangeResponse = function (response) {
        var data = JSON.parse(response);

        if (data.status === 'OK') {
            $('#changePasswordModal').modal('hide');
            this.showSuccessMessage('Password changed successfully');
            this.passwordForm[0].reset();
        } else {
            this.setError(this.currentPassword, 2, data.msg || 'Failed to change password');
        }
    };

    // Add validation functions from register.js
    this.addError = function (elem, level, message) {
        var target = elem;
        for (var i = 0; i < level - 1; i++) {
            target = target.parent();
        }
        target.parent().addClass('error');
        return target.append("<span class=\"help-inline\">" + message + "</span>");
    };

    this.removeError = function (elem, level) {
        var target = elem;
        for (var i = 0; i < level - 1; i++) {
            target = target.parent();
        }
        target.parent().removeClass('error');
        return target.children().filter('.help-inline').remove();
    };

    this.setError = function (elem, level, message) {
        this.removeError(elem, level);
        return this.addError(elem, level, message);
    };

    this.verifyRequiredField = function (field) {
        if (!field.val()) {
            this.setError(field, 2, 'Field required.');
            return false;
        } else {
            this.removeError(field, 2);
            return true;
        }
    };

    this.verifyPasswordsMatch = function (fieldA, fieldB) {
        if (!fieldA.val() || !fieldB.val()) {
            return false;
        }
        if (fieldA.val() !== fieldB.val()) {
            this.setError(fieldB, 2, 'Passwords don\'t match.');
            return false;
        } else {
            this.removeError(fieldB, 2);
            return true;
        }
    };

    // Initialize
    this.loadProfileData();
    this.initializeEditForm();
    this.initializePasswordForm();
}
