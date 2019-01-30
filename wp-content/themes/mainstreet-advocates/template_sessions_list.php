<?php
/**
 * Created by PhpStorm.
 * User: ljubi
 * Date: 12/13/2018
 * Time: 11:15 PM
 */
/* Template Name: Sessions_list */

if ( ! is_user_logged_in() ) {
	auth_redirect();
} else {
	if ( !current_user_can( 'edit_pages' ) ){
		wp_die(	'<h1>' . __( 'Not allowed to access this page.' ) . '</h1>',
			500);
	}
	get_header();
	$user = MSAvalidateUserRole();
    $data = $user->getSessionInformationList();
}

?>
<div class="main list-page">
	<div class="container-fluid">
		<h2>Sessions list</h2>
        <a href="<?php echo get_site_url();?>/sessions-add/" class="btn btn-primary btn-sm" role="button" aria-pressed="true">Add New Session</a>
		<table id="legislation" class="display" width="100%" cellspacing="0">
			<thead>
			<tr>
				<th>ID</th>
				<th>Title</th>
				<th>Info</th>
				<th>State</th>
				<th>Start Date</th>
				<th>End Date</th>
				<th>Session Year</th>
                <th>Carry Over</th>
				<th>Active</th>
			</tr>
			</thead>
			<tfoot>
			<tr>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
                <th></th>
				<th></th>
			</tr>
			</tfoot>
		</table>
	</div>
</div>
<?php get_footer(); ?>
<script>
	$(document).ready(function () {
		$('#legislation').dataTable({
            dom: 'Bfrtip',
            buttons: ['print',
                {
                    extend: 'copyHtml5',
                    exportOptions: {
                        columns: [0, ':visible']
                    }
                },
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'csvHtml5',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                'colvis'
            ],
            "bProcessing": true,
            "sAjaxSource": '<?php echo get_site_url() ?>/wp-content/themes/mainstreet-advocates/sessionapi.php?action=list',
            "aoColumns": [{
                mData: 'id',
                "mRender": function (source, type, val) {
                    return "<a href='<?php echo get_site_url(); ?>/session-edit/?id=" + source + "'>" + source + "</a>";
                }
            },
                {
                    mData: 'session_name'
                }, {
                    mData: 'session_info'
                },
                {
                    mData: 'session_state'
                }, {
                    mData: 'start_date'
                }, {
                    mData: 'end_date',
                }, {
                    mData: 'session_year',
                },{
                    mData: 'carryover',
                    mRender: function (source,type,val) {
                        return source == '1' ? 'Yes' : 'No';
                    }
                }, {
                    mData: 'is_active',
                    mRender: function (source,type,val) {
                        return source == '1' ? 'Yes' : 'No';
                    }
                }
            ]
		});

    });
</script>
