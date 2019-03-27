<?php
/**
 * Created by PhpStorm.
 * User: ljubi
 * Date: 2/9/2019
 * Time: 12:47 AM
 */

include( '../wp-load.php' );
$clients = getAllClients();

?>
    <div class="alert alert-success" style="display: none;">
    </div>
    <div class="alert alert-danger" style="display: none;">
    </div>
    <div class="wrap">
        <h1 class="wp-heading-inline mb-3">Curation Email</h1>
        <form enctype="multipart/form-data" method="post" action="">
            <div class="form-row align-items-end">
                <div class="form-group col-md-3">
                    <label for="client_id">Client:</label>
                    <select id="client_id" class="form-control" autocomplete="off" required>
                <option value="" selected>Select Client</option>
				<?php foreach ( $clients as $client ) { ?>
                    <option value="<?php echo $client->id; ?>">
						<?php echo $client->client; ?>
                    </option>
				<?php } ?>
            </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="update_status">Status:</label>
                    <select id="update_status" class="form-control" autocomplete="off">
                <option value="">Select Update</option>
            </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="user_list">Client Users:</label>
                    <select id="user_list" class="form-control" autocomplete="off">
                <option value="" selected>Select User</option>
            </select>
                </div>
                <div class="form-group form-check">
                    <input class="mt-1 form-check-input" type="checkbox" value="" id="comment_all_users" autocomplete="off">
                    <label class="ml-4 form-check-label" for="comment_all_users">
                            Select All Users
                        </label>
                </div>
            </div>

            <div class="form-row align-items-end">
                <div class="form-group col-md-9">
                    <label for="bill_comments" class="col-form-label">Email Comments</label>
                    <textarea class="form-control" rows="4" id="bill_comments" name="bill_comments" placeholder="Comments for email.."></textarea>

                </div>
                <div class="form-group">
                    <button class="btn btn-primary" name="add_mail_comment" id="add_mail_comment">Add Comment</button>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-8">
                    <button class="mr-3 btn btn-primary" name="send_email_all_users" id="send_email_all_users">Send Email To All Users</button>
                    <button class="mr-3 btn btn-primary" name="send_email_user" id="send_email_user">Send Email To User</button>
                    <button class="mr-3 btn btn-primary" name="send_email_preview" id="send_email_preview">Send Email Preview (MSA Only)</button>
                </div>
            </div>
        </form>
        <div class="content-area">
            <h1 class="wp-heading-inline mb-3 mt-3">Email Preview</h1>
            <div class="email-preview-area">

            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#bill_comments').val('');

            function ClientUsers(id) {
                var update_status = $('#update_status').val();
                $.ajax({
                    type: "POST",
                    url: "<?php echo get_site_url() ?>/wp-content/plugins/users/users_api.php",
                    data: {
                        'client_id': id,
                        'status': update_status
                    },
                    dataType: "JSON",
                    success: function(data) {
                        $('#user_list').empty();
                        $('#user_list').append('<option value="" selected>Select User</option>');
                        $.each(data, function(i, item) {
                            $('#user_list').append(new Option(data[i].user_login, data[i].ID));
                            //$('#user_list').append('<option value="' + data[i].ID + '">' + data[i].user_login + '</option>')

                        });
                    },
                    complete: function() {}
                });
            }

            $('#client_id').on('change', function() {
                var id = $(this).val();
                $(this).removeClass('is-invalid').addClass('is-valid');
                ClientUsers(id);
                getClientImports(id);
            });

            $('#add_mail_comment').on('click', function(e) {
                e.preventDefault();
                var error = "";;
                if ($('#client_id').val() === '') {
                    error += 'Please select client!';
                    $('#client_id').addClass('is-invalid');
                }
                if ($('#update_status').val() === '') {
                    error += 'Please select status update!';
                    $('#update_status').addClass('is-invalid');
                }
                if ($('#user_list').val() === '') {
                    error += "Please select client from Client Users dropdown menu!";
                    $('#user_list').addClass('is-invalid');
                }
                if (error !== "") {
                    alert(error);
                    return;
                }
                $.ajax({
                    type: "POST",
                    url: "<?php echo plugins_url( '/manage_emails_api.php', __FILE__ );?>",
                    data: {
                        client_id: $('#client_id').val(),
                        status: $('#update_status').val(),
                        user_id: $('#user_list').val(),
                        comments: $('#bill_comments').val(),
                        all_users: $('#comment_all_users').is(':checked'),
                        action: 'add_email_comment'
                    },
                    dataType: "JSON",
                    success: function(response) {
                        if (response.status) {
                            $('.alert-success').show().text(response.message).delay(3000).fadeOut();
                        } else {
                            $('.alert-danger').show().text(response.message).delay(3000).fadeOut();
                        }

                    },
                    complete: function() {}
                });
            });

            function getUserComment() {
                $.ajax({
                    type: "POST",
                    url: "<?php echo plugins_url( '/manage_emails_api.php', __FILE__ );?>",
                    data: {
                        client_id: $('#client_id').val(),
                        status: $('#update_status').val(),
                        user_id: $('#user_list').val(),
                        action: 'get_user_comments'
                    },
                    dataType: "JSON",
                    success: function(response) {
                        if (response.status) {
                            $('#bill_comments').val(response.data['comment']);
                        } else {
                            $('#bill_comments').val('');
                        }
                    }
                });
            }

            function getPreviewMail() {
                $.ajax({
                    type: "POST",
                    url: "<?php echo plugins_url( '/manage_emails_api.php', __FILE__ );?>",
                    data: {
                        client_id: $('#client_id').val(),
                        status: $('#update_status').val(),
                        user_id: $('#user_list').val(),
                        action: 'get_preview_email'
                    },
                    dataType: "HTML",
                    success: function(response) {
                        $('.email-preview-area').html(response);
                    }
                });
            }

            $('#user_list').on('change', function() {
                var user_id = $(this).val();
                var error = "";;
                if ($('#client_id').val() === '') {
                    error += 'Please select client!';
                    $('#client_id').addClass('is-invalid');
                }
                if ($('#update_status').val() === '') {
                    error += 'Please select status update!';
                    $('#update_status').addClass('is-invalid');
                }
                if ($('#user_list').val() === '') {
                    error += "Please select client from Client Users dropdown menu!";
                    $('#user_list').addClass('is-invalid');
                }
                if (error !== "") {
                    alert(error);
                    return;
                }
                $(this).removeClass('is-invalid').addClass('is-valid');
                getUserComment();
                getPreviewMail();
            });

            $('#send_email_preview').on('click', function(e) {
                e.preventDefault();
                if ($('#client_id').val() === '') {
                    alert('Please select client before clicking on "' + $(this).text().trim() + '"!');
                    return;
                }
                if ($('#update_status').val() === '') {
                    alert('Please select status update from dropdown menu before sending emails to all clients!');
                    return;
                }
                if ($('#user_list').val() === '') {
                    alert('Please select user from dropdown menu before sending email to client!');
                    return;
                }
                $(this).text('Sending email').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: "<?php echo plugins_url( '/manage_emails_api.php', __FILE__ );?>",
                    data: {
                        client_id: $('#client_id').val(),
                        status: $('#update_status').val(),
                        user_id: $('#user_list').val(),
                        action: 'send_email_preview'
                    },
                    dataType: "JSON",
                    success: function(response) {
                        if (response.status) {
                            $('.alert-success').show().text(response.message).delay(3000).fadeOut();
                        } else {
                            $('.alert-danger').show().text(response.message).delay(3000).fadeOut();
                        }
                        $('#send_email_preview').text('Send Email Preview (MSA Only)').prop('disabled', false);
                    }
                });
            });

            $('#send_email_user').on('click', function(e) {
                e.preventDefault();
                if ($('#client_id').val() === '') {
                    alert('Please select client before clicking on "' + $(this).text().trim() + '"!');
                    return;
                }
                if ($('#update_status').val() === '') {
                    alert('Please select status update from dropdown menu before sending emails to all clients!');
                    return;
                }
                if ($('#user_list').val() === '') {
                    alert('Please select user from dropdown menu before sending email to client!');
                    return;
                }
                $(this).text('Sending email').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: "<?php echo plugins_url( '/manage_emails_api.php', __FILE__ );?>",
                    data: {
                        client_id: $('#client_id').val(),
                        status: $('#update_status').val(),
                        user_id: $('#user_list').val(),
                        action: 'send_daily_email_user'
                    },
                    dataType: "JSON",
                    success: function(response) {
                        if (response.status) {
                            $('.alert-success').show().text(response.message).delay(3000).fadeOut();
                        } else {
                            $('.alert-danger').show().text(response.message).delay(3000).fadeOut();
                        }
                        $('#send_email_user').text('Send Email To User').prop('disabled', false);
                    }
                });
            });
            $('#update_status').on('change', function() {
                if ($(this).val() !== '') {
                    $(this).removeClass('is-invalid').addClass('is-valid');
                } else {
                    alert('Please select status date from dropdown.');
                    return;
                }
            });
            $('#send_email_all_users').on('click', function(e) {
                e.preventDefault();
                if ($('#client_id').val() === '') {
                    alert('Please select client before clicking on "' + $(this).text().trim() + '"!');
                    return;
                }
                if ($('#update_status').val() === '') {
                    alert('Please select status update from dropdown menu before sending emails to all clients!');
                    return;
                }
                $(this).text('Sending email').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: "<?php echo plugins_url( '/manage_emails_api.php', __FILE__ );?>",
                    data: {
                        client_id: $('#client_id').val(),
                        status: $('#update_status').val(),
                        action: 'send_daily_mail_all'
                    },
                    dataType: "JSON",
                    success: function(response) {
                        if (response.status) {
                            $('.alert-success').show().text(response.message).delay(3000).fadeOut();
                        } else {
                            $('.alert-danger').show().text(response.message).delay(3000).fadeOut();
                        }
                        $('#send_email_all_users').text('Send Email To Users').prop('disabled', false);
                    }
                });
            });

            function getClientImports(client_id) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo plugins_url( '/manage_emails_api.php', __FILE__ );?>",
                    data: {
                        client_id: client_id,
                        action: 'get_imports',
                    },
                    dataType: "JSON",
                    success: function(response) {
                        $('#update_status').children('option:not(:first)').remove();
                        $.each(response, function(i, item) {
                            $('#update_status').append(new Option(response[i].import_date, response[i].id));
                        });
                    }
                });
            }
        });
    </script>
