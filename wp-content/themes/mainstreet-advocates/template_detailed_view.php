<?php /* Template Name: Detailed view */ ?>
<?php
if ( ! isset( $_GET['id'] ) ) {
	wp_die(
		'<h1>' . __( 'Page is not properly called!' ) . '</h1>',
		500
	);
} else {

	$id   = $_GET["id"];
	$user = MSAvalidateUserRole();
	$row  = $user->getLegislationBillDetail( $id );
	get_header();

}
?>
<script type="text/javascript" src="https://addevent.com/libs/atc/1.6.1/atc.min.js" async defer></script>
<script type="text/javascript">
    window.addeventasync = function() {
        addeventatc.settings({
            appleical: {
                show: true,
                text: "iCal"
            },
            google: {
                show: true,
                text: "Google"
            },
            outlook: {
                show: true,
                text: "Outlook"
            },
            outlookcom: {
                show: false,
                text: "Outlook.com <em>(online)</em>"
            },
            yahoo: {
                show: false,
                text: "Yahoo <em>(online)</em>"
            }
        });
    };

</script>
<!--Facebook share-->
<script>
    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s);
        js.id = id;
        js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.2';
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

</script>
<?php if ( ! $user->user_is_visitor )  { ?>
<a class="map-toggle-btn" href="<?php echo get_site_url() ?>/legislation-list/"><i class="fas fa-list"></i>Back to List</a>
<?php }?>
<div class="container detailed_view">
   
    <div class="row">
        <!--       main  content -->
        <div class="col-md-9">
            <section id="main-info">
                <h3 class="mb-3">
                    <?php if ( ! $user->user_is_visitor ) { ?>
                    <?php if ( $row->priority ) { ?>
                    <i class="star fas fa-star"></i>
                    <?php } else { ?>
                    <i class="star far fa-star"></i>
                    <?php }
					} ?>
                    <?php echo $row->state . ' ' . $row->type . ' ' . $row->number; ?>
                </h3>

                <hr class="w-25 ml-0">
                <table>
                    <tr>
                        <td>
                            Sponsors
                        </td>
                        <td>
                            <?php if (!empty($row->sponsor_url) ){?>
                            <a href="<?php echo $row->sponsor_url; ?>" target="_blank">
                                <?php echo $row->sponsor_name; ?></a>
                            <?php }else{ echo $row->sponsor_name;}?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Session
                        </td>
                        <td>
                            <?php echo $row->session; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Title
                        </td>
                        <td>
                            <?php echo $row->title; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Abstract
                        </td>
                        <td>
                            <?php echo $row->abstract; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Categorie(s)
                        </td>
                        <td>
                            <?php echo implode( ',', $row->categories ) ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Keyword(s)
                        </td>
                        <td>
                            <?php echo implode( ', ', $row->keywords ) ?>
                        </td>
                    </tr>
                </table>
            </section>
            <br>
            <section id="latest-action">
                <h3>Latest Action</h3>
                <div class="progress" style="height:40px">
                    <?php for ( $x = 1; $x <= $row->legislation_status; $x ++ ) { ?>
                    <div class="progress-bar" role="progressbar" style="width:10%; height:40px">
                        <?php echo $x; ?>
                    </div>
                    <?php } ?>
                </div>
                <div>
                    <span><strong>Status </strong>
                        <?php echo $row->legislation_status . '-' . $row->status_val; ?> </span>
                </div>
            </section>
            <br>
            <?php if ( ! $user->user_is_visitor ) { ?>
            <div class="notes notes-page">
                <div class="container notes-container">
                    <div class="row">
                        <div class="col-6">
                            <h2>Notes</h2>
                        </div>
                        <div class="col-6 text-right">
                            <a value="New Note" class="button gradient-bg mt-0" id="addNote"><i class="fa fa-plus"></i> New Note</a>
                        </div>
                    </div>
                    <!-- row -->
                    <?php
				        foreach ( $row->notes as $note ) {
					        ?>
                    <div class="row single-note single-note-<?php echo $note->id; ?>">
                        <div class="col-8">
                            <h4>
                                <?php echo $note->user->display_name; ?>
                            </h4>
                            <small>Updated
                                <?php echo $note->note_timestamp; ?></small>
                        </div>
                        <div class="col-4 text-right note-icons">
                            <?php
							        // check if post user_id is current user
							        if ( $note->user->ID == $user->user_id ) {
								        ?>
                            <a><i class="fa fa-edit" data-note-id="<?php echo $note->id; ?>"></i></a>
                            <a><i class="fa fa-trash" data-note-id="<?php echo $note->id; ?>"></i></a>
                            <?php } ?>
                        </div>
                        <div class="col-12 data-note-text-<?php echo $note->id; ?>">
                            <?php echo esc_html( $note->note_text ); ?>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <?php } ?>
        </div>
        <!--       sidebar -->
        <div class="col-md-3">
            <?php if ( ! $user->user_is_visitor ) {
            $page = get_page_link();
            
            ?>
            <div class="social mb-3">
                <div id="fb-root"></div>
                <div class="fb-share-button" data-href="https://test.com/?id=<?php echo $id; ?>" data-layout="button" data-size="large" data-mobile-iframe="true"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $page ?>" ><i class="fab fa-facebook-f"></i></a></div>

                <a class="twitter-share-button" href="https://twitter.com/intent/tweet?text=<?php echo get_page_link().'?id='.$id ?>" data-size="small" target="_blank"><i class="fab fa-twitter"></i></a>

                <a href="mailto:?cc= &subject=Please check the following &body=<?php echo get_page_link().'?id='.$id ?>" target="_top"><i class="far fa-envelope"></i></a>
            </div>


            <?php } ?>
            <div>
                <?php if ( $row->textUploaded === '1' ) { ?>
                <a class="button gradient-bg  btn-small mb-4 w-100" href="<?php echo get_site_url(); ?>/detail-bill-text/?id=<?php echo $row->id ?>&entity=legislation" target="_blank" style="text-align: center !important;">View Full Bill Text</a>
                <?php } else { ?>
                <a class="button btn-small mb-4 w-100" target="_blank" style="text-align: center !important; border-bottom: 3px solid #d3d9e0 !important;">View Full
                    Bill Text</a>
                <?php } ?>
            </div>
            <br>
            <section id="Additional-info" class="contact-info mb-5">
                <h2>Info</h2>
                <h5>Additional Session Info</h5>
                <ul class="p-0 m-0">
                    <li>
                        State:<span>
                            <?php echo isset( $row->session_information->session_state ) ? esc_html( $row->session_information->session_state ) : ''; ?></span>
                    </li>
                    <li>Convenes:
                        <span>
                            <?php echo( isset( $row->session_information->start_date ) ? esc_html( $row->session_information->start_date ) : '' ); ?></span>
                    </li>
                    <li>Adjoums:
                        <span>
                            <?php echo( isset( $row->session_information->adjourn_date ) ? esc_html( $row->session_information->adjourn_date ) : '' ); ?></span>
                    </li>
                    <li>Cary over: <span><?php echo ($row->session_information->carryover)? 'Yes' : 'No';?></span></li>
                    <li>Prefilling:
                        <span>
                            <?php echo( isset( $row->session_information->prefiling ) ? esc_html( $row->session_information->prefiling ) : '' ); ?></span>
                    </li>
                    <li>Additional info:
                        <span>
                            <?php echo( isset( $row->session_information->additional_info ) ? esc_html( $row->session_information->additional_info ) : '' ); ?></span>
                    </li>
                </ul>


            </section>
            <br>
            <section id="Additional-info" class="opening-hours">
                <h2>Related Documents</h2>
                <div class="hearings">
                    <?php
					if ( isset( $row->related_bills ) ) {
						foreach ( $row->related_bills as $bill ) {
							?>
                    <ul class="p-0 m-0">
                        <li>Type<span>
                                <?php echo $bill->type; ?></span></li>
                        <li>Number <span>
                                <?php echo $bill->number; ?></span></li>
                        <li>URL <span><a target="_blank" href="<?php echo $bill->url; ?>">check here</a></span></li>
                    </ul>
                    <?php }
					} ?>
                </div>
            </section>
            <br>
            <?php if ( ! $user->user_is_visitor ) {?>
            <section id="Additional-info" class="opening-hours">
                <h2>Hearings</h2>
                <div class="hearings">
                    <ul class="p-0 m-0">
                        <li>
                            Place:<span>
                                <?php echo( isset( $row->hearing_information->place ) ? esc_html( $row->hearing_information->place ) : '' ); ?></span>
                        </li>
                        <li>Date:
                            <span>
                                <?php echo( isset( $row->hearing_information->date ) ? esc_html( $row->hearing_information->date ) : '' ); ?></span>
                        </li>
                        <li>Time:
                            <span>
                                <?php echo( isset( $row->hearing_information->time ) ? esc_html( $row->hearing_information->time ) : '' ); ?></span>
                        </li>
                        <li>Committee:
                            <span>
                                <?php echo( isset( $row->hearing_information->committee ) ? esc_html( $row->hearing_information->committee ) : ''); ?></span>
                        </li>
                    </ul>
                    <?php if ( isset( $row->hearing_information->place ) and isset( $row->hearing_information->date ) ) { ?>
                    <div title="Add to Calendar" class="addeventatc">
                        Add to Calendar
                        <span class="start">
                            <?php echo $row->hearing_information->date . ' ' . $row->hearing_information->time; ?></span>
                        <span class="timezone">.</span>
                        <span class="title">Hearing -
                            <?php echo $row->hearing_information->committee; ?></span>
                        <span class="location">
                            <?php echo $row->hearing_information->place; ?></span>
                        <span class="description ">Bill/legislation Number:
                            <?php echo $row->external_id; ?> <br> Bill/legislation title:
                            <?php echo $row->state . ' ' . $row->type . ' ' . $row->number; ?></span>
                        <span class="client">aIsdvMxakzwXLcaAzmca59414</span>
                    </div>
                    <?php } else { ?>
                        <button class="button btn-small mt-4 w-100 text-center" style="border-bottom: none;"><i class="far fa-calendar-alt"></i> Add to calendar
                    </button>
                    <?php } ?>
                </div>
            </section>
            <?php }?>
        </div>
    </div>
</div>
<?php get_footer() ?>
<script>
    var states = {
        "AL": "America/Chicago",
        "AK": "America/Anchorage",
        "AS": "Pacific/Pago_Pago",
        "AZ": "America/Phoenix",
        "AR": "America/Chicago",
        "CA": "America/Los_Angeles",
        "CO": "America/Denver",
        "CT": "America/New_York",
        "DE": "America/New_York ",
        "DC": "America/New_York",
        "FL": "America/New_York",
        "GA": "America/New_York",
        "GU": "Pacific/Guam",
        "HI": "Pacific/Honolulu",
        "ID": "America/Denver",
        "IL": "America/Chicago",
        "IN": "America/Indiana/Indianapolis",
        "IA": "America/Chicago",
        "KS": "America/Chicago",
        "KY": "America/New_York",
        "LA": "America/Chicago",
        "ME": "America/New_York",
        "MH": "Pacific/Majuro",
        "MD": "America/New_York",
        "MA": "America/New_York",
        "MI": "America/Detroit",
        "MN": "America/Chicago",
        "MS": "America/Chicago",
        "MO": "America/Chicago",
        "MT": "America/Denver",
        "NE": "America/Chicago",
        "NV": "America/Los_Angeles",
        "NH": "America/New_York",
        "NJ": "America/New_York",
        "NM": "America/Denver",
        "NY": "America/New_York",
        "NC": "America/New_York",
        "ND": "America/Chicago",
        "MP": "Pacific/Saipan",
        "OH": "America/New_York",
        "OK": "America/Chicago",
        "OR": "America/Los_Angeles",
        "PW": "Pacific/Palau",
        "PA": "America/New_York",
        "PR": "America/Puerto_Rico",
        "RI": "America/New_York",
        "SC": "America/New_York",
        "SD": "America/Chicago",
        "TN": "America/Chicago",
        "TX": "America/Chicago",
        "UT": "America/Denver",
        "VT": "America/New_York",
        "VI": "America/St_Thomas",
        "VA": "America/New_York",
        "WA": "America/Los_Angeles",
        "WV": "America/New_York",
        "WI": "America/Chicago",
        "WY": "America/Denver"
    };
    $(document).ready(function() {

        function updateTimezone() {
            $('.timezone').html(states[<?php echo "'$row->state'";?>])
        }

        updateTimezone();
        <?php if ( ! $user->user_is_visitor ) { ?>
        var x = " <div class=\"row single-note note-add-new \">\n" +
            "                            <div class=\"col-12\">\n" +
            "                                <h4>New Note</h4>\n" +
            "                            </div>\n" +
            "                            <div class=\"col-12\">\n" +
            "                                <textarea name=\"name\" rows=\"12\" cols=\"80\" placeholder=\"Enter text here...\" id='new_notes_text'></textarea>\n" +
            "                            </div>\n" +
            "                            <div class=\"col-12 text-right\">\n" +
            "                                <a value=\"New Note\" class=\"button gradient-bg\" id='new_note_add'><i class=\"fa fa-check\"></i> Add Note</a>\n" +
            "                            </div>\n" +
            "                        </div>";

        $('.notes').on('click','.fa-trash',function () {
            var note_id = this.getAttribute('data-note-id');
            removeNote(note_id);
        });

        $('.notes').on('click', '.fa-edit', function() {
            var note_id = this.getAttribute('data-note-id');
            var note_text = $('.data-note-text-' + note_id).text().trim();
            $(this).hide();
            var edit_section = '  <div class="row single-note edit-note">\n' +
                '                <div class="col-12">\n' +
                '                    <h4>Edit Note</h4>\n' +
                '                </div>\n' +
                '                <div class="col-12">\n' +
                '                    <textarea name="name" rows="12" cols="80">' + note_text + '</textarea>\n' +
                '                </div>\n' +
                '                <div class="col-12 text-right">\n' +
                '                    <a value="Save Note" class="button gradient-bg" data-note-id="' + note_id + '" id="edit_note"><i class="fa fa-check"></i> Save Changes</a>\n' +
                '                    <a value="Cancel Note" class="button gradient-bg" data-note-id="' + note_id + '" id="cancel_edit_note">Cancel</a>\n' +
                '                </div>\n' +
                '            </div>';
            $(".notes-container").append(edit_section);
        });

        function removeNote(id) {
            $.ajax({
                type: 'POST',
                url: '<?php echo get_site_url() ?>/wp-content/themes/mainstreet-advocates/notes_api.php',
                data: {
                    "action": "remove",
                    "type": "legislation",
                    "bill_id": <?php echo $row->id?>,
                    'note_id': id
                },
                dataType: 'JSON',
                success: function(response) {
                    if (response.status === true) {
                        $(".single-note-" + id).remove();
                    }
                }
            })
        }

        function addNewNote(user, note_id, timestamp, text) {
            $("#addNote").show();
            $(".note-add-new").remove();
            $('.notes-container>div:eq(0)').after('<div class="row single-note single-note-' + note_id + '">\n' +
                '                <div class="col-8">\n' +
                '                    <h4>' + user + '</h4>\n' +
                '                    <small>Updated ' + timestamp + '</small>\n' +
                '                </div>\n' +
                '                <div class="col-4 text-right note-icons">\n' +
                '                    <a><i class="fa fa-edit"  data-note-id="' + note_id + '" ></i></a>\n' +
                '                    <a><i class="fa fa-trash" data-note-id="' + note_id + '"></i></a>\n' +
                '                </div>\n' +
                '                <div class="col-12 data-note-text-' + note_id + '"> \n' + text +
                '                   ' +
                '                </div>\n' +
                '            </div>');
        }

        $("#addNote").click(function() {
            $(".notes-container").append(x);
            $("#addNote").hide();
        });

        $('.notes').on('click', '#cancel_edit_note', function() {
            var note_id = this.getAttribute('data-note-id');
            $(this).closest('.edit-note').remove();
            $('.fa-edit[data-note-id=' + note_id + ']').show();
        });

        $('.notes').on('click', '#edit_note', function() {
            var new_text_note = $(this).closest('.edit-note').find('textarea').val();
            var note_id = this.getAttribute('data-note-id');
            var element = $('.single-note-' + note_id);
            $.ajax({
                type: 'POST',
                url: '<?php echo get_site_url() ?>/wp-content/themes/mainstreet-advocates/notes_api.php',
                data: {
                    "action": "edit",
                    "note_text": new_text_note,
                    "type": "legislation",
                    "id": note_id
                },
                dataType: 'JSON',
                success: function(response) {
                    if (response.status === true) {
                        $('.edit-note').remove();
                        element.remove();
                        addNewNote(response.edit_user, note_id, response.note_timestamp, new_text_note);
                    }
                }
            })
        });

        $('.notes').on('click', '#new_note_add', function() {
            var text_notes = $('#new_notes_text').val() //.replace(/\r\n|\r|\n/g,"\n");
            $.ajax({
                type: 'POST',
                url: '<?php echo get_site_url() ?>/wp-content/themes/mainstreet-advocates/notes_api.php',
                data: {
                    "action": "add",
                    "note_text": text_notes,
                    "type": "legislation",
                    "id": <?php echo $row->id?>
                },
                dataType: 'JSON',
                success: function(response) {
                    if (response.status === true) {
                        addNewNote(response.insert_user, response.insert_id, response.insert_timestamp, text_notes);
                    }
                }
            })
        });

        $('.fa-star').on('click', function() {
            var element = $(this);
            var status = "enable";
            if (element.hasClass('fas')) {
                status = "disable";
            }
            $.ajax({
                type: 'POST',
                url: '<?php echo get_site_url() ?>/wp-content/themes/mainstreet-advocates/legapi.php',
                data: {
                    "id": <?php echo $row->id?>,
                    "type": "legislation",
                    "action": "priority",
                    "status": status
                },
                dataType: 'JSON',
                success: function(response) {
                    if (response.status === true) {
                        if (status === 'disable') {
                            element.removeClass('fas').addClass('far');
                        } else {
                            element.removeClass('far').addClass('fas');
                        }
                    }
                }
            })
        });
        <?php } ?>
    });

</script>
