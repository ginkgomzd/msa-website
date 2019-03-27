<link href="../assets/vendors/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
<div class="m-grid__item m-grid__item--fluid m-wrapper">
	<div class="m-content">
		<!--Begin::Section-->
		<div class="row">
			<div class="col-xl-12">
				<!--begin::Portlet-->
				<div class="m-portlet m-portlet--mobile">
					<div class="m-portlet__head">
						<div class="m-portlet__head-caption">
							<div class="m-portlet__head-title">
                                         <span class="m-portlet__head-icon">
													<i class="flaticon-users "></i>
												</span>
								<h3 class="m-portlet__head-text">
									<?= $breadcumb['portlet'] ?>
								</h3>
							</div>
						</div>
					</div>
					<div class="m-portlet__body">
						<!--begin: Datatable -->
						<table class="table table-striped- table-bordered table-hover table-checkable" id="m_table_1">
							<thead>
							<tr>
								<th>
									User ID
								</th>
								<th>
									Short Name
								</th>
								<th>
									First Name
								</th>
								<th>
									Last Name
								</th>
								<th>
									Email
								</th>
								<th>
									Monitor
								</th>
								<th>
									Status
								</th>
							</tr>
							</thead>
							<tbody>
							<?php
							foreach ($user_list as $data) {
								echo '<tr>';
								echo '<td>' . $data['idusers'] . '</td>';
								echo '<td>' . $data['user_shortname'] . '</td>';
								echo '<td>' . (($data['user_firstname'])??'N/A') . '</td>';
								echo '<td>' . (($data['user_lastname'])??'N/A') . '</td>';
								echo '<td>' . (($data['user_email'])??'N/A') . '</td>';
								?>
								<td>
                                <span class="m-switch m-switch--sm m-switch--icon m-switch--success" style="padding-top: 0.75rem">
																		<label>
																			   <?php
																			   if (in_array($data['idusers'],$user_monitoring_list)){
																				   echo '<input checked="checked" value="'.$data["idusers"].'" type="checkbox" onchange="check(this);">';
																			   }else{
																				   echo '<input  value="'.$data["idusers"].'" type="checkbox" onchange="check(this);">';
																			   }

																			   ?>
																			<span></span>
																		</label>
																	</span>
								</td>
								<?php
								if ($data['user_status'] == 1) {
									echo '<td><span class="m-badge  m-badge--success m-badge--wide">Active</span></td>';
								} else {
									echo '<td><span class="m-badge  m-badge--danger m-badge--wide">Inactive</span></td>';
								}
								?>
								</tr>

							<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
				<!--end::Portlet-->
			</div>
		</div>
	</div>
	<!--End::Section-->
</div>
</div>
<script src="../assets/vendors/datatables/datatables.bundle.js" type="text/javascript"></script>
<script>
    var DatatablesBasicBasic = {
        init: function () {
            var e;
            (e = $("#m_table_1")).DataTable(
                {
                    responsive: !0,
                    lengthMenu: [5, 10, 25, 50],
                    pageLength: 10,
                    language: {lengthMenu: "Display _MENU_"},
                    order: [[1, "desc"]],
                    headerCallback: function (e, a, t, n, s) {
                        e.getElementsByTagName("th")[0].innerHTML = '\n                    <label class="m-checkbox m-checkbox--single m-checkbox--solid m-checkbox--brand">\n                        <input type="checkbox" value="" class="m-group-checkable">\n                        <span></span>\n                    </label>'
                    },
                    columnDefs: [{
                        targets: 0,
                        width: "30px",
                        className: "dt-right",
                        orderable: !1,
                        render: function (e, a, t, n) {
                            return '\n<label class="m-checkbox m-checkbox--single m-checkbox--solid m-checkbox--brand">\n                            <input type="checkbox" value="" class="m-checkable">\n                            <span></span>\n                        </label>'
                        }
                    }, {
                        targets: -1,orderable: !1
                    }
                    ]
                }), e.on("change", ".m-group-checkable", function () {
                var e = $(this).closest("table").find("td:first-child .m-checkable"), a = $(this).is(":checked");
                $(e).each(function () {
                    a ? ($(this).prop("checked", !0), $(this).closest("tr").addClass("active")) : ($(this).prop("checked", !1), $(this).closest("tr").removeClass("active"))
                })
            }), e.on("change", "tbody tr .m-checkbox", function () {
                $(this).parents("tr").toggleClass("active")
            })
        }
    };
    jQuery(document).ready(function () {
        DatatablesBasicBasic.init()
    });
    function check(element) {
        $.ajax({
            type: 'POST',
            url: '/settings/monitoruser',
            data: {"id":element.value,"action":element.checked},
            dataType: 'JSON',
            success: function (response) {
                if (response.status == true) {
                    return swal({
                        title: "",
                        text: response.message,
                        type: "success",
                        confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"
                    })
                } else {
                    var textmessage = '';
                    for (var i = 0; i < response.message.length; i++) {
                        textmessage += response.message[i];
                    }
                    mApp.scrollTo("#add_user"), swal({
                        title: "",
                        text: textmessage,
                        type: "error",
                        confirmButtonClass: "btn btn-secondary m-btn m-btn--wide"
                    })
                }
            }
        })

    }
</script>