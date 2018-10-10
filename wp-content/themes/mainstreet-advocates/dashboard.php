<?php /* Template Name: Dashboard */ ?>
<?php get_header(); ?>
<?php 
    
    $user_id=get_current_user_id();
    $categories = explode(",",getCategoriesByUser($user_id)); 

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
                    <option value="null">Category</option>
                   <?php foreach ($categories as $category) { ?>
                    <option value="<?php echo trim($category); ?>"><?php echo $category;?></option>
                   <?php } ?>
            </select>
            </div>
            <div class="form-group">
                <select name="priority" id="priority" class="select-b">
                    <option value="%">Prioritized</option>
                    <option value="1">Yes</option> 
                    <option value="0">No</option>
                </select>
            </div>
            <div class="form-group">
                <select name="status" id="status" class="select-b">
                <option value="0">Status</option>
            </select>
            </div>
        </div>
        <div class="col-md-9 col-lg-10">
           <a class="map-toggle-btn" href="<?php echo get_site_url()?>/legislation-list/"><i class="fa fa-list" aria-hidden="true"></i></a>
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
        
        var bills;
        var category = 'null';
        var priority = null;
       
        
        

        function myFunction(parameter) {
            
            var parameter = category + '&pr=' + priority + '&bl=' + bills;
            
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
//                "export": {
//                    "enabled": true
//                },
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
            console.log(priority);
            console.log('c'+category);
            console.log('b'+ bills);
        });
        
        
        $('.select-b').select2();

    });

</script>

<?php get_footer(); ?>
