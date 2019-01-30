<?php
// Include the library
include( '../wp-load.php' );

$clients = getAllClients();
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
      integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
<form enctype="multipart/form-data" method="post" action="">
    <div class="col-md-12">
        <h2>User management</h2>
        <div class="form-group form-horizontal">
            <div class="row">
                <div class="col-md-9">
                    <label for="client_id">Select Client:</label>
                    <select name="client_id" id="client_id" autocomplete="off">
                        <option value="" selected>Select Client</option>
						<?php foreach ( $clients as $client ) { ?>
                            <option value="<?php echo $client->id; ?>">
								<?php echo $client->client; ?>
                            </option>
						<?php } ?>
                    </select>
                </div>
                <div class="col-md-9">
                    <label for="user_list">Select User:</label>
                    <select name="user_id" id="user_list" autocomplete="off">
                        <option value="" selected>Select User</option>
                    </select>
                </div>
                <div class="col-md-3 ">
                    <input type="submit" id="update" class="btn btn-primary" value="Update" name="submit">
                </div>
            </div>
        </div>
    </div>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
               aria-selected="true">States</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
               aria-controls="profile" aria-selected="false">Categories</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab"
               aria-controls="contact" aria-selected="false">Keywords</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="col-md-6">
                <table class="table thead-dark table-striped table-bordered" id="states_table">
                    <thead>
                    <tr>
                        <th>Frontend</th>
                        <th>E-mail</th>
                        <th>State</th>
                    </tr>
                    </thead>
                    <tbody id="tbodyid">
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <div class="col-md-6">
                <table class="table thead-dark table-striped table-bordered" id="category_table">
                    <thead>
                    <tr>
                        <th>Frontend</th>
                        <th>E-mail</th>
                        <th>Categories</th>
                    </tr>
                    </thead>
                    <tbody id="tbodyid">
                    </tbody>
                </table>
            </div>
        </div>

        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
            <div class="col-md-6">
                <table class="table thead-dark table-striped table-bordered" id="keywords_table">
                    <thead>
                    <tr>
                        <th>Frontend</th>
                        <th>E-mail</th>
                        <th>Keywords</th>
                    </tr>
                    </thead>
                    <tbody id="tbodyid">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
</form>

<script>
    $('#update').hide();

    $(document).ready(function () {
        var states = {
            "AL": "Alabama",
            "AK": "Alaska",
            "AS": "American Samoa",
            "AZ": "Arizona",
            "AR": "Arkansas",
            "CA": "California",
            "CO": "Colorado",
            "CT": "Connecticut",
            "DE": "Delaware",
            "DC": "District Of Columbia",
            "FM": "Federated States Of Micronesia",
            "FL": "Florida",
            "GA": "Georgia",
            "GU": "Guam",
            "HI": "Hawaii",
            "ID": "Idaho",
            "IL": "Illinois",
            "IN": "Indiana",
            "IA": "Iowa",
            "KS": "Kansas",
            "KY": "Kentucky",
            "LA": "Louisiana",
            "ME": "Maine",
            "MH": "Marshall Islands",
            "MD": "Maryland",
            "MA": "Massachusetts",
            "MI": "Michigan",
            "MN": "Minnesota",
            "MS": "Mississippi",
            "MO": "Missouri",
            "MT": "Montana",
            "NE": "Nebraska",
            "NV": "Nevada",
            "NH": "New Hampshire",
            "NJ": "New Jersey",
            "NM": "New Mexico",
            "NY": "New York",
            "NC": "North Carolina",
            "ND": "North Dakota",
            "MP": "Northern Mariana Islands",
            "OH": "Ohio",
            "OK": "Oklahoma",
            "OR": "Oregon",
            "PW": "Palau",
            "PA": "Pennsylvania",
            "PR": "Puerto Rico",
            "RI": "Rhode Island",
            "SC": "South Carolina",
            "SD": "South Dakota",
            "TN": "Tennessee",
            "TX": "Texas",
            "UT": "Utah",
            "VT": "Vermont",
            "VI": "Virgin Islands",
            "VA": "Virginia",
            "WA": "Washington",
            "WV": "West Virginia",
            "WI": "Wisconsin",
            "WY": "Wyoming"
        };

        function addTableRow(rowcategory, rowid, rowisFrontActive, rowisMailActive) {
            $('#states_table tbody').append('<tr><td><input type="hidden" name="removefront_' + rowid + '" id="removefront_' + rowid + '" value="0"><input type="checkbox" id="' + rowid + '_frontend" class="front" name="' + rowid + '_frontend" onclick="this.previousSibling.value=1-this.previousSibling.value"></td><td><input type="hidden" name="removemail_' + rowid + '" id="removemail_' + rowid + '" value="0"><input type="checkbox" id="' + rowid + '_mail"class="front" name="' + rowid + '_mail" onclick="this.previousSibling.value=1-this.previousSibling.value"></td><td>' + rowcategory + '</td></tr>');

            if (rowisFrontActive === '1') {
                $('#' + rowid + '_frontend').prop('checked', true);
            }

            if (rowisMailActive === '1') {
                $('#' + rowid + '_mail').prop('checked', true);
            }
        }

        function ClientUsers(id) {
            $.ajax({
                type: "POST",
                url: "<?php echo get_site_url();?>/wp-content/plugins/users/users_api.php",
                data: {
                    'client_id': id
                },
                dataType: "JSON",
                success: function (data) {
                    $('#user_list').empty();
                    $('#user_list').append('<option value="" selected>Select User</option>');
                    $.each(data, function (i, item) {
                        $('#user_list').append('<option value="' + data[i].ID + '">' + data[i].user_login + '</option>')

                    });
                },
                complete: function () {
                }
            });
        }

        function UsersData(id) {
            $.ajax({
                type: "POST",
                url: "<?php echo get_site_url();?>/wp-content/plugins/users/users_api.php",
                data: {
                    'user_id': id
                },
                dataType: "JSON",
                success: function (data) {
                    $.each(data, function (i, item) {
                        if (data[i].type == 'category') {
                            $('#category_table tbody').append('<tr><td><input type="hidden" name="removefront_category_' + data[i].id + '" id="removefront_' + data[i].id + '" value="0"><input type="checkbox" id="' + data[i].id + '_frontend" class="front" name="' + data[i].id + '_frontend" onclick="this.previousSibling.value=1-this.previousSibling.value"></td><td><input type="hidden" name="removemail_category_' + data[i].id + '" id="removemail_' + data[i].id + '" value="0"><input type="checkbox" id="' + data[i].id + '_mail"class="front" name="' + data[i].id + '_mail" onclick="this.previousSibling.value=1-this.previousSibling.value"></td><td>' + data[i].category + '</td></tr>');

                            if (data[i].isfrontactive === '1') {
                                $('#' + data[i].id + '_frontend').prop('checked', true);
                            }

                            if (data[i].ismailactive === '1') {
                                $('#' + data[i].id + '_mail').prop('checked', true);
                            }
                        } else if (data[i].type == 'state') {
                            $('#states_table tbody').append('<tr><td><input type="hidden" name="removefront_state_' + data[i].id + '" id="removefront_' + data[i].id + '" value="0"><input type="checkbox" id="' + data[i].id + '_frontend" class="front" name="' + data[i].id + '_frontend" onclick="this.previousSibling.value=1-this.previousSibling.value"></td><td><input type="hidden" name="removemail_state_' + data[i].id + '" id="removemail_' + data[i].id + '" value="0"><input type="checkbox" id="' + data[i].id + '_mail"class="front" name="' + data[i].id + '_mail" onclick="this.previousSibling.value=1-this.previousSibling.value"></td><td>' + states[data[i].category] + '</td></tr>');

                            if (data[i].isfrontactive === '1') {
                                $('#' + data[i].id + '_frontend').prop('checked', true);
                            }

                            if (data[i].ismailactive === '1') {
                                $('#' + data[i].id + '_mail').prop('checked', true);
                            }
                        } else if (data[i].type == 'keyword') {
                            $('#keywords_table tbody').append('<tr><td><input type="hidden" name="removefront_keyword_' + data[i].id + '" id="removefront_' + data[i].id + '" value="0"><input type="checkbox" id="' + data[i].id + '_frontend" class="front" name="' + data[i].id + '_frontend" onclick="this.previousSibling.value=1-this.previousSibling.value"></td><td><input type="hidden" name="removemail_keyword_' + data[i].id + '" id="removemail_' + data[i].id + '" value="0"><input type="checkbox" id="' + data[i].id + '_mail"class="front" name="' + data[i].id + '_mail" onclick="this.previousSibling.value=1-this.previousSibling.value"></td><td>' + data[i].category + '</td></tr>');

                            if (data[i].isfrontactive === '1') {
                                $('#' + data[i].id + '_frontend').prop('checked', true);
                            }

                            if (data[i].ismailactive === '1') {
                                $('#' + data[i].id + '_mail').prop('checked', true);
                            }
                        }
                    });
                },
                complete: function () {
                }
            });
        }

        $('#client_id').on('change', function () {
            if (this.value) {
                $('#update').show();
                var id = $(this).val();
                $(".state").prop('checked', false);
                $(".front").prop('checked', false);
                $(".mail").prop('checked', false);
                ClientUsers(id);
            }
        });
        $('#user_list').on('change', function () {
            $("#tbodyid tr").remove();
            var id = $(this).val();
            UsersData(id);
            $('#update').show();
        });
    });

</script>
<?php
if ( isset( $_POST['submit'] ) and $_POST['submit'] === 'Update' and isset( $_POST['user_id'] )) {
	$user_id = $_POST['user_id'];
	foreach ( $_POST as $field => $value ) {
		if ( strpos( $field, 'removefront_' ) !== false and $value == 1 ) {
			updateUserSettings( $field, "isfrontactive", $user_id );
		} else if ( strpos( $field, 'removemail_' ) !== false and $value == 1 ) {
			updateUserSettings( $field, "ismailactive", $user_id );
		}
	}
}

?>