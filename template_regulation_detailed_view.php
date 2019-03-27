<?php
/* Template Name: Regulation_Detailed_view */
if ( ! isset( $_GET['id'] ) ) {
	wp_die(
		'<h1>' . __( 'Page is not properly called.' ) . '</h1>',
		500
	);
} else {
	get_header();
	$id   = $_GET["id"];
	$user = MSAvalidateUserRole();
	$row  = $user->getRegulationBillDetail( $id );
}
?>
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

<a class="map-toggle-btn" href="<?php echo get_site_url() ?>/legislation-list/"><i class="fas fa-list"></i>Back to List</a>

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

					<?php echo $row->tracking_key; ?> </h3>

                <hr class="w-25 ml-0">
                <table>
                    <tr>
                        <td>
                            State
                        </td>
                        <td>
							<?php echo $row->state; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Agency Name
                        </td>
                        <td>
							<?php echo $row->agency_name; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Type
                        </td>
                        <td>
							<?php echo $row->type; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            State Action Type
                        </td>
                        <td>
							<?php echo $row->state_action_type; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Code Citation
                        </td>
                        <td>
							<?php echo $row->code_citation; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Register Citation
                        </td>
                        <td>
							<?php echo $row->register_citation; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Description
                        </td>
                        <td>
			                <?php echo $row->description; ?>
                        </td>
                    </tr>
                </table>
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
                                <a value="New Note" class="button gradient-bg mt-0" id="addNote"><i
                                            class="fa fa-plus"></i> New Note</a>
                            </div>
                        </div>
                        <!-- row -->
						<?php
						foreach ( $row->notes as $note ) {
							?>
                            <div class="row single-note single-note-<?php echo $note->id; ?>">
                                <div class="col-8">
                                    <h4><?php echo $note->user->display_name; ?></h4>
                                    <small>Updated <?php echo $note->note_timestamp; ?></small>
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
			<?php if ( ! $user->user_is_visitor ) { ?>
                <div class="social mb-3">
                    <div id="fb-root"></div>
                    <div class="fb-share-button" data-href="https://test.com/?id=<?php echo $id; ?>" data-layout="button" data-size="large" data-mobile-iframe="true"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo get_page_link() ?>" ><i class="fab fa-facebook-f"></i></a></div>
                    <a class="twitter-share-button" href="https://twitter.com/intent/tweet?text=<?php echo get_page_link().'?id='.$id ?>" data-size="small" target="_blank"><i class="fab fa-twitter"></i></a>
                    <a href="mailto:?cc= &subject=Please check the following &body=<?php echo get_page_link().'?id='.$id ?>" target="_top"><i class="far fa-envelope"></i></a>
                </div>
			<?php } ?>
            <div>
				<?php if ( $row->textUploaded === '1' ) { ?>
                    <a class="button gradient-bg  btn-small mb-4 w-100"
                       href="<?php echo get_site_url(); ?>/detail-bill-text/?id=<?php echo $row->external_id ?>"
                       target="_blank" style="text-align: center !important;">View Full Bill Text</a>
				<?php } else { ?>
                    <a class="button btn-small mb-4 w-100" target="_blank"
                       style="text-align: center !important; border-bottom: 3px solid #d3d9e0 !important;">View Full
                        Bill Text</a>
				<?php } ?>
            </div>
            <br>
        </div>
    </div>
</div>
<?php get_footer() ?>
<script>
	<?php if ( ! $user->user_is_visitor ) { ?>
    $(document).ready(function () {
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

        $('.notes').on('click', '.fa-edit', function () {
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
                data: {"action": "remove", "type": "regulation", "bill_id":<?php echo $row->id?>, 'note_id': id},
                dataType: 'JSON',
                success: function (response) {
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

        $("#addNote").click(function () {
            $(".notes-container").append(x);
            $("#addNote").hide();
        });

        $('.notes').on('click', '#cancel_edit_note', function () {
            var note_id = this.getAttribute('data-note-id');
            $(this).closest('.edit-note').remove();
            $('.fa-edit[data-note-id=' + note_id + ']').show();
        });

        $('.notes').on('click', '#edit_note', function () {
            var new_text_note = $(this).closest('.edit-note').find('textarea').val();
            var note_id = this.getAttribute('data-note-id');
            var element = $('.single-note-' + note_id);
            $.ajax({
                type: 'POST',
                url: '<?php echo get_site_url() ?>/wp-content/themes/mainstreet-advocates/notes_api.php',
                data: {"action": "edit", "note_text": new_text_note, "type": "regulation", "id": note_id},
                dataType: 'JSON',
                success: function (response) {
                    if (response.status === true) {
                        $('.edit-note').remove();
                        element.remove();
                        addNewNote(response.edit_user, note_id, response.note_timestamp, new_text_note);
                    }
                }
            })
        });

        $('.notes').on('click', '#new_note_add', function () {
            var text_notes = $('#new_notes_text').val()//.replace(/\r\n|\r|\n/g,"\n");
            $.ajax({
                type: 'POST',
                url: '<?php echo get_site_url() ?>/wp-content/themes/mainstreet-advocates/notes_api.php',
                data: {"action": "add", "note_text": text_notes, "type": "regulation", "id":<?php echo $row->id?>},
                dataType: 'JSON',
                success: function (response) {
                    if (response.status === true) {
                        addNewNote(response.insert_user, response.insert_id, response.insert_timestamp, text_notes);
                    }
                }
            })
        });
        $('.fa-star').on('click', function () {
            var element = $(this);
            var status = "enable";
            if (element.hasClass('fas')) {
                status = "disable";
            }
            $.ajax({
                type: 'POST',
                url: '<?php echo get_site_url() ?>/wp-content/themes/mainstreet-advocates/legapi.php',
                data: {"id": <?php echo $row->id?>, "type": "regulation", "action": "priority", "status": status},
                dataType: 'JSON',
                success: function (response) {
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
    });
	<?php }?>
</script>
