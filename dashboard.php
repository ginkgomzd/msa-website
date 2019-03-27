<?php /* Template Name: Dashboard */ ?>
<?php
if ( ! is_user_logged_in() ) {
	auth_redirect();
} else {
	get_header();
	$user = MSAvalidateUserRole();
	$url  = get_site_url();
}

?>
<script src="https://www.amcharts.com/lib/4/core.js"></script>
<script src="https://www.amcharts.com/lib/4/maps.js"></script>
<script src="https://www.amcharts.com/lib/4/geodata/usaTerritoriesHigh.js"></script>
<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>
<style>
    .legislation-status {
        display: none;
    }

    .federal-bills {
        display: none;
    }
</style>
<div class="main map-page container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-2 filter">
            <h4>Filter</h4>
            <p>Total Records: <span class="total-num">0</span></p>
            <div class="form-group">
                <select name="bills" id="bills" class="select-b" autocomplete="off">
                    <option value="">Bills</option>
                    <option value="legislation">Legislations</option>
                    <option value="regulation">Regulation</option>
                    <option value="hearing">Hearings</option>
                </select>
            </div>
            <div class="form-group">
                <select name="category" id="category" class="select-b" autocomplete="off">
                    <option value="">Category</option>
					<?php
					foreach ( $user->user_categories as $category => $value ) {
						if ( $value['isfrontactive'] == true ) {
							echo "<option value='{$category}'>{$category}</option>";
						}
					}
					?>
                </select>
            </div>
            <div class="form-group">
                <select name="priority" id="priority" class="select-b" autocomplete="off">
                    <option value="">Prioritized</option>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
            <div class="form-group legislation-status">
                <select name="status" id="status" class="select-b" autocomplete="off">
                    <option value="">Status</option>
                    <option value="1">Profiled</option>
                    <option value="2">Committee 1st Chamber</option>
                    <option value="3">Hearing Scheduled</option>
                    <option value="4">Passed by 1st Committee</option>
                    <option value="5">Floor Vote 1st Chamber</option>
                    <option value="6">Passed 1st Chamber</option>
                    <option value="7">Passed Committee in 2nd Chamber</option>
                    <option value="8">Passed 2nd Chamber</option>
                    <option value="9">Vetoed or Sent Back to Legislature</option>
                    <option value="10">Signed by Governor</option>
                </select>
            </div>
        </div>
        <div class="col-md-9 col-lg-10">
            <div class="federal-bills gradient-bg button">
                <img class="" src="../wp-content/themes/mainstreet-advocates/images/usa-map-o.png" alt="">
                <button class="federal-bills-btn">Select Federal Bills</button>
            </div>

            <a class="map-toggle-btn" href="<?php echo get_site_url() ?>/legislation-list/"><i class="fa fa-list"
                                                                                               aria-hidden="true"></i></a>
            <div id="chartdiv"></div>
        </div>
    </div>
</div>
<?php
$url    = get_site_url();
$json   = file_get_contents( $url . '/wp-content/themes/mainstreet-advocates/states.json' );
$states = json_decode( $json );
?>
<script>
    $(document).ready(function () {
        var bills;
        var category = null;
        var priority = null;
        var polygonSeries;
        var chart;
        var polygonTemplate;

        function myFunction(parameter) {
            // Themes begin
            am4core.useTheme(am4themes_animated);

            // Create map instance
            chart = am4core.create("chartdiv", am4maps.MapChart);

            // Set map definition
            chart.geodata = am4geodata_usaTerritoriesHigh;

            // Set projection
            chart.projection = new am4maps.projections.Miller();

            // Create map polygon series
            polygonSeries = chart.series.push(new am4maps.MapPolygonSeries());

            //Set min/max fill color for each area
            polygonSeries.heatRules.push({
                property: "fill",
                target: polygonSeries.mapPolygons.template,
                min: chart.colors.getIndex(1).brighten(1),
                max: chart.colors.getIndex(1).brighten(-0.3)
            });

            let mapbg = polygonSeries.mapPolygons.template;
            mapbg.fill = am4core.color("#d8dee7");

            // Make map load polygon data (state shapes and names) from GeoJSON
            polygonSeries.useGeodata = true;

            // Set heatmap values for each state


            // Set up heat legend
            let heatLegend = chart.createChild(am4maps.HeatLegend);
            heatLegend.series = polygonSeries;
            heatLegend.align = "right";
            heatLegend.width = am4core.percent(25);
            heatLegend.marginRight = am4core.percent(4);
            heatLegend.minValue = 0;
            heatLegend.maxValue = 300;

            polygonSeries.mapPolygons.template.events.on("over", function (ev) {
                if (!isNaN(ev.target.dataItem.value)) {
                    heatLegend.valueAxis.showTooltipAt(ev.target.dataItem.value)
                    //console.log(ev.target.dataItem.dataContext.modalUrl);
                } else {
                    heatLegend.valueAxis.hideTooltip();
                }
            });
            polygonSeries.mapPolygons.template.events.on("hit", function (ev) {
                if (!isNaN(ev.target.dataItem.value)) {
                    $.fancybox({
                        "href": ev.target.dataItem.dataContext.modalUrl,
                        "title": ev.target.dataItem.dataContext.title,
                        "type": "iframe",
                        'width': '80%',
                        'height': '60%'
                    });
                    // onsole.log(ev.target.dataItem.dataContext);
                    //chart.openPopup("We clicked on <strong>" + ev.target.dataItem.dataContext.name + "</strong>");

                } else {
                    heatLegend.valueAxis.hideTooltip();
                }
            });
            polygonSeries.mapPolygons.template.events.on("out", function (ev) {
                heatLegend.valueAxis.hideTooltip();
            });
            // Set up custom heat map legend labels using axis ranges
            var minRange = heatLegend.valueAxis.axisRanges.create();
            minRange.value = heatLegend.minValue;
            minRange.label.text = "Little";
            var maxRange = heatLegend.valueAxis.axisRanges.create();
            maxRange.value = heatLegend.maxValue;
            maxRange.label.text = "A lot!";

            // Blank out internal heat legend value axis labels
            heatLegend.valueAxis.renderer.labels.template.adapter.add("text", function (labelText) {
                return "";
            });
            // Configure series tooltip
            polygonTemplate = polygonSeries.mapPolygons.template;

            // Create hover state and set alternative fill color
            var hs = polygonTemplate.states.create("hover");
            hs.properties.fill = am4core.color("#26539b");
            // Add zoom control
            chart.zoomControl = new am4maps.ZoomControl();
            chart.zoomControl.align = "left";
            //Create button zoom in zoom out and go home
            var button = chart.chartContainer.createChild(am4core.Button);
            button.padding(5, 5, 5, 5);
            button.width = 20;
            button.align = "left";
            button.marginRight = 10;
            button.events.on("hit", function () {
                chart.goHome();
            });
            button.icon = new am4core.Sprite();
            button.icon.path = "M16,8 L14,8 L14,16 L10,16 L10,10 L6,10 L6,16 L2,16 L2,8 L0,8 L8,0 L16,8 Z M16,8";

        }

        myFunction();
        getAjaxData();

        // trigger change on each dropdown change
        $('#bills, #category, #priority,#status').on('change', function () {
            if ($('#bills').val() === 'legislation' && $('.legislation-status').css('display') === 'none') {
                $('.legislation-status').show();
            } else if ($('#bills').val() !== 'legislation') {
                $('.legislation-status').hide();
            }
            $('.federal-bills').hide();
            getAjaxData();

        });

        function getAjaxData() {
            $.ajax({
                type: "POST",
                url: "<?php echo get_site_url() ?>/wp-content/themes/mainstreet-advocates/dashboardapi.php",
                data: populateDataArray(),
                dataType: "JSON",
                success: function (result) {
                    if (jQuery.isEmptyObject(result.data)) {
                        //dummy data if there is no data otherwise map will not be shown
                        polygonSeries.data = [{"id": 'US-CA'}];
                        polygonTemplate.tooltipText = "State Name: {name}";
                    } else {
                        if ($('#bills').val() !== '') {
                            $.each(result.data, function (index, value) {
                                if (value.id === 'US-US') {
                                    console.log('pokazi');
                                    $('.federal-bills').show();
                                    $('.federal-bills-btn').attr('data-modalUrl', value.modalUrl);
                                    return false;
                                }
                            });
                        }
                        polygonSeries.data = result.data;
                        polygonTemplate.tooltipText = getToolTipText();
                    }
                    $('.total-num').html(result.total);
                },
                complete: function () {
                }
            });
        }

        $('.federal-bills-btn').on('click', function () {
            $.fancybox({
                "href": $(this).attr('data-modalUrl'),
                "title": $('#bills').val(),
                "type": "iframe",
                'width': '80%',
                'height': '60%'
            });
        });

        function populateDataArray() {
            return {
                'category': $('#category').val(),
                'type': $('#bills').val(),
                'priority': $('#priority').val(),
                'status': $('#status').val()
            }
        }


        function getToolTipText() {
            var tooltipText = "State Name: {name}\n";
            switch ($('#bills').val()) {
                case 'legislation':
                    tooltipText += "Legislations Count: {legislation}";
                    break;
                case 'regulation':
                    tooltipText += "Regulations Count: {regulation}";
                    break;
                case "hearing":
                    tooltipText += "Hearings Count: {hearing}";
                    break;
                default:
                    tooltipText += "Legislation Count: {legislation}\n" +
                        "Regulations Count: {regulation}\n" +
                        "Hearing Count: {hearing}\n";
            }
            return tooltipText;
        }

        $('.select-b').select2();

    });

</script>

<?php get_footer(); ?>
