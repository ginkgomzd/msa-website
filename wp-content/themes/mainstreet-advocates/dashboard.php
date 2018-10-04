<?php /* Template Name: Dashboard */ ?>
<?php get_header(); ?>

<style>
    #chartdiv {
        width: 90%;
        height: 750px;
    }

</style>
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

<script src="https://www.amcharts.com/lib/3/ammap.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>
<script src="https://www.amcharts.com/lib/3/maps/js/usaLow.js"></script>
<script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
<link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<?php 

    global $wpdb;
    $cat_query="SELECT pname FROM `profile_match`";
    $categories = $wpdb->get_results($cat_query,OBJECT );

    function getNumber($state) {
        global $wpdb;
        $count_query="SELECT count(id) FROM `legislation` where state like '$state'";
        $localauthoritypoints = $wpdb->get_var($count_query); 

        echo $localauthoritypoints;
    }

?>
<div class="main map-page container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-2 filter">
            <h4>Filter</h4>
            <p>total records:
                <span class="total-num"><?php getNumber('%') ?></span> </p>
            <div class="form-group">
                <select name="bills" id="bills" class="select-b">
                    <option value="%">Bills</option>
                    <option value="legislation">Legislations</option>
                    <option value="regulation">Regulation</option>
                    <option value="hearings">Hearings</option>      
                </select>
            </div>
            <div class="form-group">
                <select name="category" id="category" class="select-b">
                    <option value="%">Category</option>
                   <?php foreach ($categories as $category) { ?>
                    <option value="<?php echo $category->pname ?>"><?php echo $category->pname ?></option>
                   <?php } ?>
            </select>
            </div>
            <div class="form-group">
                <select name="priority" id="priority" class="select-b">
                    <option value="%">Prioritized</option>
                    <option value="1">Yes</option> 
                    <option value="NULL">No</option>
                </select>
            </div>
            <div class="form-group">
                <select name="status" id="status" class="select-b">
                <option value="0">Status</option>
            </select>
            </div>
        </div>
        <div class="col-md-9 col-lg-10">
           <a class="map-toggle-btn" href="#"><i class="fa fa-list" aria-hidden="true"></i></a>
            <div id="chartdiv"></div>
        </div>
    </div>
</div>
<?php 
    $json = file_get_contents('http://localhost/msa_test/wp-content/themes/mainstreet-advocates/states.json');
    $states=json_decode($json); 
?>

<script>
    $(document).ready(function() {

        function myFunction(parameter) {
            var map = AmCharts.makeChart("chartdiv", {
                "type": "map",
                "theme": "light",
                "colorSteps": 20,
                "dataProvider": {
                    "map": "usaLow",
                    "areas": [
                        // ooping through states
                        <?php foreach ($states as $state) { ?> {
                            "id": "US-<?php echo $state->abbreviation; ?>",
                            "modalUrl": "<?php echo get_site_url() ?>/dashboard-list/?cat=" + parameter + "&st=<?php echo $state->abbreviation; ?>",
                            "selectable": true,
                            "value": <?php getNumber($state->abbreviation); ?>,
                        },
                        <?php  } ?>
                    ]
                },
                "areasSettings": {
                    "autoZoom": true,
                    "selectedColor": "#CC0000",
                    "rollOverColor": "#404040",
                    "rollOverOutlineColor": "#FFFFFF",
                },
                "export": {
                    "enabled": true
                },
                "valueLegend": {
                    "right": 20,
                    "minValue": "little",
                    "maxValue": "a lot!"
                },
                "listeners": [{
                    "event": "clickMapObject",
                    "method": function(event) {
                        $.fancybox({
                            "href": event.mapObject.modalUrl,
                            "title": event.mapObject.title,
                            "type": "iframe",
                            'width': '80%',
                            'height': '60%'
                        });

                    }
                }]
            });
            
        }

        var bills;
        var category;
        var priority;
        var parameter;

        myFunction();

        $('#bills').on('change', function() {
            bills = this.value;
            category = $('#category').val();
            priority = $('#priority').val();
            parameter = category + '&pr=' + priority + '&bl=' + bills;
            myFunction(parameter);
        });
        
        $('#category').on('change', function() {
            category = this.value;
            priority = $('#priority').val();
            bills = $('#bills').val();
            parameter = category + '&pr=' + priority + '&bl=' + bills;
            myFunction(parameter);
        });

        $('#priority').on('change', function() {
            priority = this.value;
            category = $('#category').val();
            bills = $('#bills').val();
            parameter = category + '&pr=' + priority + '&bl=' + bills;
            myFunction(parameter);
        });
        
        
        $('.select-b').select2();

    });

</script>

<?php get_footer(); ?>
