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

        /*
        .federal-bills {
            display: none;
        }
*/
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
                <?php $_lastimports_dates = $user->getLastImportTimestamps(); ?>
                <?php $_lastweekimportcounts = $user->getImportsLastWeek();?>
                <?php $_bills = $user->getClientLegislation(); ?>
                <?php $_user_regulations = $user->getClientRegulation(); ?>
	            <?php $_user_upcoming_hearings = $user->getCountUpcomingHearings(); ?>
                <div class="numbers-bills">
                    <p class="numbers-heading">Bills</p>
                    <table class="numbers-table">
                        <tr>
                            <td>
                                Total Number of Bills
                            </td>
                            <td>
                                <?php echo count($_bills); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Last import
                            </td>
                            <td>
	                            <?php echo date( 'F j, Y', strtotime( $_lastimports_dates['legislation'] ) );  ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Bills Added or Updated This Week:
                            </td>
                            <td>
                               <?php echo $_lastweekimportcounts['leg_counter'];?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Bills Added or Updated Since Last Import
                            </td>
                            <td>
	                            <?php echo count($user->getClientLastUpdateLegislation());?>
                            </td>
                        </tr>
                    </table>
                </div>


                <div class="numbers-regulations">
                    <p class="numbers-heading">Regultations</p>
                    <table class="numbers-table">
                        <tr>
                            <td>
                                Total Number of Regulations
                            </td>
                            <td>
                                <?php echo count($_user_regulations); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Last import
                            </td>
                            <td>
	                            <?php echo date( 'F j, Y', strtotime( $_lastimports_dates['regulation'] ) );  ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Regulations Added or Updated This Week
                            </td>
                            <td>
	                            <?php echo $_lastweekimportcounts['reg_counter'];?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Regultations Added or Update Since Last Import
                            </td>
                            <td>
	                            <?php echo count($user->getClientLastUpdateRegulation());?>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="numbers-hearings">
                    <p class="numbers-heading">Hearings</p>
                    <table class="numbers-table">
                        <tr>
                            <td>
                                Total Number of Upcoming hearings
                            </td>
                            <td>
                               <?php echo $_user_upcoming_hearings;?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Last import
                            </td>
                            <td>
	                            <?php echo date( 'F j, Y', strtotime( $_lastimports_dates['hearing'] ) );  ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="col-md-9 col-lg-10">
                <!--
                <div class="federal-bills gradient-bg button">
                    <img class="" src="../wp-content/themes/mainstreet-advocates/images/usa-map-o.png" alt="">
                    <button class="federal-bills-btn">Select Federal Bills</button>
                </div>
-->

                <div class="federal-bills federal-bills-btn map-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 451 278"><style type="text/css">
</style><path class="map-icon" d="M228 271c-1 0-2 0-3 0 -10.3-1.4-17-7-21.4-16.4 -3-6.3-7.2-12-11-17.8 -3.8-5.8-11.4-6.2-15.1-1 -3.7 5.1-5.1 5.4-10.7 2.8 -3.6-1.6-5.8-4.3-7.2-7.9 -3.2-8.2-9.2-14.1-15.8-19.5 -4.7-3.8-7.5-3.7-12.4 0.2 -1.4 1.1-2.7 2-4.5 1.9 -13.8-0.5-27.6-0.8-40.1-8.1 -10.8-6.3-21.1-14.2-35-12 -3.4 0.5-4.6-2.7-5.8-5.3 -3.1-6.3-6.6-12.2-13.1-15.9 -5.5-3.1-11.3-10-14.3-15.9 -1.2-2.4-2.6-4.7-3.7-7.1 -0.8-1.8-1.7-3.7-2-5.6 -1.3-10.9-3.4-21.7-9-31.4 0-6 0-12 0-18 2.7-5.5 4.3-11.2 3.7-17.4 -0.4-4.1 1-7.8 2.6-11.5 3.1-7.6 8.1-14.2 10.4-22.1 2.5-8.8 2.7-17.7 0.9-26.7 -0.4-1.9-0.8-4.2 1-5.6 2-1.6 3.7 0.3 5.4 1.2 2.2 1.2 4.6 2.8 6.8 0.6 3-3.1 6.5-2.8 10.2-2.1 4.1 0.8 8.2 1.3 12.3 2.1 27.6 5.1 55.4 9.6 83.5 11.4 25.5 1.6 51 0.9 76.4 1 5.6 0 10.9-0.9 15.8-3.9 3-1.8 5.7-1.5 8 1.3 2.1 2.5 4.8 3.4 8 3.4 2.2 0 4.4 0.1 6.3 1.2 4.2 2.5 8.8 2.8 13.5 2.9 6.1 0.1 8.1 3.3 6 9.2 -0.5 1.5-1.9 3.2 0.2 4.4 1.7 1 3.2-0.4 4.4-1.5 2.4-2.2 4.1-5.3 7.6-6.3 2.1-0.6 4.5-0.9 4.9 1.7 1 6.8 4.8 5.2 9 3.6 6.6-2.5 12.6-2.3 19 1.8 5.9 3.8 6.7 10 9.4 15.4 1 1.9 1.3 4.1 3 5.6 5.9 5.3 7.8 11.7 5.4 19.3 -0.5 1.7-1.4 3.6 0.3 5.1 2.1 1.7 4.1 0.6 6-0.5 5.1-3 9.1-7.2 12.8-11.6 1.5-1.8 3.9-3.4 3.8-6.2 -0.2-4.4 2.3-6.1 6.3-6.9 3.3-0.6 6.4-1.6 7.9-5.2 3.9-9.1 8.3-17.5 20.1-17.6 1.1 0 2.3-0.4 3.4-0.9 9-4.2 14.4-8 14.3-16.6 0-6.8 0.1-13.2 5.4-18.3 3.7 0 7.3 0 11 0 2.2 2.3 3.9 4.9 5.5 7.7 2.4 4.4 5 8.7 9.5 11.3 0 3 0 6 0 9 -5.6 3.7-10.3 8.4-14.7 13.4 -5.2 5.8-4.6 10.2 1.6 13.9 4.4 2.6 4.5 5.9 0.4 8.9 -2.9 2.1-6.8 3.4-8.1 7.1 -1.4 4-4.1 6.2-7.5 8.2 -2.8 1.7-6.1 3.5-6.1 7.3 0 11.3-3.5 21.9-6.6 32.6 -0.7 2.5-0.7 4.9 0.8 7.2 5.6 8.6 5.1 12.9-2.3 19.7 -5.7 5.2-9.3 12.2-14.6 17.8 -4 4.3-7 10.3-10.5 15.5 -7.4 11.1-7.1 18.1 3.1 30.5 8.3 10 13.3 21.4 12.7 34.7 -0.3 6.9-5.1 9.2-11 5.4 -6.8-4.3-11.6-10.8-17.8-15.9 -2.9-2.4-4.5-6.4-4.7-10.7 -0.4-8.5-7.4-13.4-15.4-11 -3.4 1-6.8 1-9.7-0.6 -9-4.9-17.5-1.1-26 1.3 -3.6 1-5.3 3.3-2.4 7.1 2 2.6 1.8 6-1.5 6.2 -6 0.4-12.5 3.3-18.2 0.6 -7-3.4-14.2-2.8-21.4-2.8 -5 0-9.8 1.8-12.9 5.6 -4.7 5.6-10.5 9.6-16.7 13.3 -3.9 2.3-5.6 6-4.2 10.7 0.6 2.1 0.9 4.2 1.5 6.3C232.7 267.8 231.7 270 228 271z"/></svg>

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
        $(document).ready(function() {
            var bills;
            var category = null;
            var priority = null;
            var polygonSeries;
            var chart;
            var polygonTemplate;
            var maxRange;
            let heatLegend;
            var minRange;
            function myFunction() {
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
                polygonSeries.exclude = ["US-AS","US-VI","US-GU","US-MP"];
                polygonSeries.useGeodata = true;
                polygonSeries.propertyFields.fill = "name";
                // Set heatmap values for each state


                // Set up heat legend
                heatLegend = chart.createChild(am4maps.HeatLegend);
                heatLegend.series = polygonSeries;
                heatLegend.align = "right";
                heatLegend.width = am4core.percent(25);
                heatLegend.marginRight = am4core.percent(4);
                //heatLegend.minValue = 0;
                //heatLegend.maxValue = 300;

                polygonSeries.mapPolygons.template.events.on("over", function(ev) {
                    if (!isNaN(ev.target.dataItem.value)) {
                        heatLegend.valueAxis.showTooltipAt(ev.target.dataItem.value)
                    } else {
                        heatLegend.valueAxis.hideTooltip();
                    }
                });
                polygonSeries.mapPolygons.template.events.on("hit", function(ev) {
                    if (!isNaN(ev.target.dataItem.value)) {
                        window.open(ev.target.dataItem.dataContext.modalUrl, '_self');
                    } else {
                        heatLegend.valueAxis.hideTooltip();
                    }
                });
                polygonSeries.mapPolygons.template.events.on("out", function(ev) {
                    heatLegend.valueAxis.hideTooltip();
                });
                // code commented out used for getting long and lat on map
                /*var imageSeries = chart.series.push(new am4maps.MapImageSeries());
                var mapImage = imageSeries.mapImages.template;
                var mapMarker = mapImage.createChild(am4core.Sprite);
                mapMarker.path = "M4 12 A12 12 0 0 1 28 12 C28 20, 16 32, 16 32 C16 32, 4 20 4 12 M11 12 A5 5 0 0 0 21 12 A5 5 0 0 0 11 12 Z";
                mapMarker.width = 32;
                mapMarker.height = 32;
                mapMarker.scale = 0.7;
                mapMarker.fill = am4core.color("#3F4B3B");
                mapMarker.fillOpacity = 0.8;
                mapMarker.horizontalCenter = "middle";
                mapMarker.verticalCenter = "bottom";
                // Set up custom heat map legend labels using axis ranges
                chart.seriesContainer.events.on("hit", function(ev) {
                    var coords = chart.svgPointToGeo(ev.svgPoint);
                    var marker = imageSeries.mapImages.create();
                    console.log(coords.latitude);
                    console.log(coords.longitude);
                    marker.latitude = coords.latitude;
                    marker.longitude = coords.longitude;
                });*/


                minRange = heatLegend.valueAxis.axisRanges.create();
                minRange.value = heatLegend.minValue;
                minRange.label.text = heatLegend.minValue;
                maxRange = heatLegend.valueAxis.axisRanges.create();
                maxRange.value = heatLegend.maxValue;
                maxRange.label.text = heatLegend.maxValue;

                // Blank out internal heat legend value axis labels
                /*heatLegend.valueAxis.renderer.labels.template.adapter.add("text", function(labelText) {
					return "";
				});*/
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
                button.events.on("hit", function() {
                    chart.goHome();
                });
                button.icon = new am4core.Sprite();
                button.icon.path = "M16,8 L14,8 L14,16 L10,16 L10,10 L6,10 L6,16 L2,16 L2,8 L0,8 L8,0 L16,8 Z M16,8";


                // Place series
                var placeSeries = chart.series.push(new am4maps.MapImageSeries());
                var place = placeSeries.mapImages.template;
                //place.nonScaling = true;
                place.propertyFields.latitude = "latitude";
                place.propertyFields.longitude = "longitude";
                placeSeries.data = [{
                    latitude: 37.352,
                    longitude: -120.318,
                    name: "California",
                    hcenter: "middle",
                    vcenter: "middle"
                }, {
                    latitude: 46.986,
                    longitude: -73.103,
                    name: "Vermont",
                    hcenter: "middle",
                    vcenter: "middle"
                }, {
                    latitude: 43.817,
                    longitude: -120.730,
                    name: "Oregon",
                    hcenter: "middle",
                    vcenter: "middle"
                }, {
                    latitude: 47.484,
                    longitude: -119.983,
                    name: "Washington",
                    hcenter: "middle",
                    vcenter: "middle"
                }, {
                    latitude: 39.224,
                    longitude: -117.030,
                    name: "Nevada",
                    hcenter: "middle",
                    vcenter: "middle"
                },{
                    latitude: 43.443,
                    longitude: -114.738,
                    name: "Idaho",
                    hcenter: "middle",
                    vcenter: "middle"
                },{
                    latitude: 46.945,
                    longitude: -109.382,
                    name: "Montatana",
                    hcenter: "middle",
                    vcenter: "middle"
                },{
                    latitude: 34.362,
                    longitude: -111.711,
                    name: "Arizona",
                    hcenter: "middle",
                    vcenter: "middle"
                },{
                    latitude: 34.468,
                    longitude: -106.097,
                    name: "New Mexico",
                    hcenter: "middle",
                    vcenter: "middle"
                },{
                    latitude: 38.971,
                    longitude: -105.870,
                    name: "Colorado",
                    hcenter: "middle",
                    vcenter: "middle"
                },{
                    latitude: 43.122,
                    longitude: -107.710,
                    name: "Wyoming",
                    hcenter: "middle",
                    vcenter: "middle"
                },{
                    latitude: 44.392,
                    longitude: -100.598,
                    name: "South Dakota",
                    hcenter: "middle",
                    vcenter: "middle"
                },{
                    latitude: 47.408,
                    longitude: -100.639,
                    name: "North Dakota",
                    hcenter: "middle",
                    vcenter: "middle"
                },{
                    latitude: 41.560,
                    longitude: -100.019,
                    name: "Nebraska",
                    hcenter: "middle",
                    vcenter: "middle"

                },{
                    latitude: 23.892,
                    longitude: -115.447,
                    name: "Alaska",
                    hcenter: "middle",
                    vcenter: "middle"
                },{
                    latitude: 23.542,
                    longitude: -102.511,
                    name: "Hawaii",
                    hcenter: "middle",
                    vcenter: "middle"
                },{
                    latitude: 18.761,
                    longitude: -91.218,
                    name: "Puerto Rico",
                    hcenter: "middle",
                    vcenter: "middle"
                },{
                    latitude: 31.524,
                    longitude: -99.363,
                    name: "Texas",
                    hcenter: "middle",
                    vcenter: "middle"
                },{
                    latitude: 35.451,
                    longitude: -97.389,
                    name: "Oklahoma",
                    hcenter: "middle",
                    vcenter: "middle"
                },{
                    latitude: 38.460,
                    longitude: -98.436,
                    name: "Kansas",
                    hcenter: "middle",
                    vcenter: "middle"
                },{
                    latitude: 46.024,
                    longitude: -94.773,
                    name: "Minnesota",
                    hcenter: "middle",
                    vcenter: "middle"
                },{
                    latitude: 42.023,
                    longitude: -93.824,
                    name: "Iowa",
                    hcenter: "middle",
                    vcenter: "middle"
                },{
                    latitude: 31.448,
                    longitude: -92.624,
                    name: "Louisiana",
                    hcenter: "middle",
                    vcenter: "middle"
                },{
                    latitude: 34.808,
                    longitude: -92.631,
                    name: "Arkansas",
                    hcenter: "middle",
                    vcenter: "middle"
                },{
                    latitude: 38.510,
                    longitude: -92.812,
                    name: "Missouri",
                    hcenter: "middle",
                    vcenter: "middle"
                },{
                    latitude: 39.235,
                    longitude: -111.749,
                    name: "Utah",
                    hcenter: "middle",
                    vcenter: "middle"
                },{
                    latitude: 32.770,
                    longitude: -89.938,
                    name: "Mississippi",
                    hcenter: "middle",
                    vcenter: "middle"
                },{
                    latitude: 32.888,
                    longitude: -86.907,
                    name: "Alabama",
                    hcenter: "middle",
                    vcenter: "middle"
                },{
                    latitude: 32.849,
                    longitude: -83.789,
                    name: "Georgia",
                    hcenter: "middle",
                    vcenter: "middle"
                },{
                    latitude: 35.763,
                    longitude: -87.061,
                    name: "Tennessee",
                    hcenter: "middle",
                    vcenter: "middle"
                },{
                    latitude: 37.429,
                    longitude: -85.480,
                    name: "Kentucky",
                    hcenter: "middle",
                    vcenter: "middle"
                },{
                    latitude: 44.504,
                    longitude: -90.146,
                    name: "Wisconsin",
                    hcenter: "middle",
                    vcenter: "middle"
                },{
                    latitude: 44.242,
                    longitude: -85.364,
                    name: "Michigan",
                    hcenter: "middle",
                    vcenter: "middle"
                },{
                    latitude: 45.180,
                    longitude: -69.674,
                    name: "Maine",
                    hcenter: "middle",
                    vcenter: "middle"
                },{
                    latitude: 40.172,
                    longitude: -89.405,
                    name: "Illinois",
                    hcenter: "middle",
                    vcenter: "middle"
                },{
                    latitude: 40.061,
                    longitude: -86.297,
                    name: "Indiana",
                    hcenter: "middle",
                    vcenter: "middle"
                },{
                    latitude: 28.300,
                    longitude: -81.969,
                    name: "Florida",
                    hcenter: "middle",
                    vcenter: "middle"
                },{
                    latitude: 33.657,
                    longitude: -80.918,
                    name: "South Carolina",
                    hcenter: "middle",
                    vcenter: "middle"
                },{
                    latitude: 35.564,
                    longitude: -79.864,
                    name: "North Carolina",
                    hcenter: "middle",
                    vcenter: "middle"
                },{
                    latitude: 37.189,
                    longitude: -79.029,
                    name: "Virginia",
                    hcenter: "middle",
                    vcenter: "middle"
                },{
                    latitude: 40.172,
                    longitude: -83.200,
                    name: "Ohio",
                    hcenter: "middle",
                    vcenter: "middle"
                },{
                    latitude: 37.685,
                    longitude: -72.528,
                    name: "Delaware",
                    hcenter: "middle",
                    vcenter: "middle"
                },{
                    latitude: 38.484,
                    longitude: -81.139,
                    name: "West Virginia",
                    hcenter: "middle",
                    vcenter: "middle"
                },{
                    latitude: 36.393,
                    longitude: -74.071,
                    name: "Washington, D.C",
                    hcenter: "middle",
                    vcenter: "middle"
                },{
                    latitude: 36.992,
                    longitude: -72.767,
                    name: "Maryland",
                    hcenter: "middle",
                    vcenter: "middle"
                },{
                    latitude:38.509,
                    longitude: -71.347,
                    name: "New Jersey",
                    hcenter: "middle",
                    vcenter: "middle"
                },{
                    latitude:40.690,
                    longitude: -78.102,
                    name: "Pennsylvania",
                    hcenter: "middle",
                    vcenter: "middle"
                },{
                    latitude: 42.702,
                    longitude: -76.165,
                    name: "New York",
                    hcenter: "middle",
                    vcenter: "middle"
                },{
                    latitude: 47.808,
                    longitude: -71.801,
                    name: "New Hampshire",
                    hcenter: "middle",
                    vcenter: "middle"
                },{
                    latitude: 46.204,
                    longitude: -75.205,
                    name: "Massachusetts",
                    hcenter: "middle",
                    vcenter: "middle"
                },{
                    latitude:39.831,
                    longitude: -71.359,
                    name: "Connecticut",
                    hcenter: "middle",
                    vcenter: "middle"
                },{
                    latitude:40.815,
                    longitude:-69.060,
                    name: "Rhode Island",
                    hcenter: "middle",
                    vcenter: "middle"
                }];

                /*var circle = place.createChild(am4core.Circle);
                circle.radius = 5;
                circle.fill = am4core.color("#e33");
                circle.stroke = am4core.color("#fff");
                circle.strokeWidth = 2;*/

                var label = place.createChild(am4core.Label);
                label.padding(15, 15, 15, 15);
                label.propertyFields.text = "name";
                label.propertyFields.horizontalCenter = "hcenter";
                label.propertyFields.verticalCenter = "vcenter";

                var lineSeries = chart.series.push(new am4maps.MapLineSeries());
                lineSeries.data = [{
                    "multiGeoLine": [
                        [
                            { "latitude": 44.0563, "longitude": -72.7556 },
                            { "latitude":46.7523, "longitude": -73.0891973 }
                        ],
                        [
                            { "latitude": 41.7015, "longitude": -71.5896 },
                            { "latitude": 40.953, "longitude": -69.552 }
                        ],
                        [
                            { "latitude": 38.964, "longitude": -75.558 },
                            { "latitude": 37.945, "longitude": -72.731 }
                        ],
                        [
                            { "latitude": 38.927, "longitude": -77.023 },
                            { "latitude": 36.566, "longitude": -74.140 }
                        ],
                        [
                            { "latitude":39.428, "longitude": -76.720 },
                            { "latitude": 37.033, "longitude": -72.883 }
                        ],
                        [
                            { "latitude":39.769, "longitude": -74.425 },
                            { "latitude": 38.732, "longitude": -71.780 }
                        ],
                        [
                            { "latitude":43.799, "longitude":-71.500 },
                            { "latitude":47.627, "longitude": -71.801 }
                        ],
                        [
                            { "latitude":42.424, "longitude":-72.973 },
                            { "latitude":45.775, "longitude": -75.138 }
                        ],
                        [
                            { "latitude":41.488, "longitude":-72.778 },
                            { "latitude":40.058, "longitude": -71.258 }
                        ]
                    ]
                }];
                setTimeout(function () {
                    chart.goHome();
                },1500);
            }

            myFunction();
            getAjaxData();

            // trigger change on each dropdown change
            $('#bills, #category, #priority,#status').on('change', function() {
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
                    success: function(result) {
                        if (jQuery.isEmptyObject(result.data)) {
                            //dummy data if there is no data otherwise map will not be shown
                            polygonSeries.data = [{
                                "id": 'US-CA'
                            }];
                            polygonTemplate.tooltipText = "State Name: {name}";
                        } else {
                            if ($('#bills').val() !== '') {
                                $.each(result.data, function(index, value) {
                                    if (value.id === 'US-US') {
                                        $('.federal-bills').show();
                                        $('.federal-bills-btn').attr('data-modalUrl', value.modalUrl);
                                        return false;
                                    }
                                });
                            }
                            polygonSeries.data = result.data;
                            polygonTemplate.tooltipText = getToolTipText();
                            var maxValue =  Math.max.apply(Math,result.data.map(function(o){return o.value;}));

                            heatLegend.series = polygonSeries;
                            heatLegend.maxValue = maxValue;
                            heatLegend.minValue = 0;//Math.min.apply(Math,result.data.map(function(o){return o.value;}));
                            maxRange.value = heatLegend.maxValue;
                            maxRange.label.text = heatLegend.maxValue;
                            minRange.value =heatLegend.minValue;
                            minRange.label.text = heatLegend.minValue;
                            heatLegend.valueAxis.renderer.labels.template.fontSize = 15;

                        }
                        $('.total-num').html(result.total);
                    },
                    complete: function() {}
                });
            }

            $('.federal-bills-btn').on('click', function() {
                window.open($(this).attr('data-modalUrl'), '_self');
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
                        tooltipText += "Legislation Count: {legislation}";
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

        <?php wp_footer(); ?>