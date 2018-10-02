<?php /* Template Name: Dashboard */ ?>
<?php get_header(); ?>
<style>
    #chartdiv {
        width: 100%;
        height: 750px;
    }

</style>

<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.css" />
<script src="https://www.amcharts.com/lib/3/ammap.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>
<script src="https://www.amcharts.com/lib/3/maps/js/usaLow.js"></script>
<script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
<link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
<div class="container">
<div class="row">
<select name="" id="" class="col-md-3"></select>
<select name="" id="" class="col-md-3"></select>
<select name="" id="" class="col-md-3"></select>
</div>
<div id="chartdiv"></div>

</div>
<?php 
    while($x <= 5) {
    echo '{
                "id": "US-AL",
                "modalUrl": "<?php echo get_site_url() ?>/legislation-list/",
                "selectable": true,
                "value": 500
            },';
    $x++;
} 
?>
<script>
    var map = AmCharts.makeChart("chartdiv", {
        "type": "map",
        "theme": "light",
        "colorSteps": 20,
        "dataProvider": {
            "map": "usaLow",
            "areas": [{
                "id": "US-AL",
                "modalUrl": "<?php echo get_site_url() ?>/legislation-list/",
                "selectable": true,
                "value": 500
            }, {
                "id": "US-AK",
                "modalUrl": "<?php echo get_site_url() ?>/legislation-list/",
                "selectable": true,
                "value": 555
            }, {
                "id": "US-AZ",
                "modalUrl": "<?php echo get_site_url() ?>/legislation-list/",
                "selectable": true,
                "value": 999
            }, {
                "id": "US-AR",
                "modalUrl": "<?php echo get_site_url() ?>/legislation-list/",
                "selectable": true,
                "value": 1500
            }, {
                "id": "US-CA",
                "modalUrl": "<?php echo get_site_url() ?>/legislation-list/",
                "selectable": true,
                "value": 1500
            }, {
                "id": "US-CO",
                "value": 1500
            }, {
                "id": "US-CT",
                "value": 1500
            }, {
                "id": "US-DE",
                "value": 1500
            }, {
                "id": "US-FL",
                "value": 1500
            }, {
                "id": "US-GA",
                "value": 1500
            }, {
                "id": "US-HI",
                "value": 1500
            }, {
                "id": "US-ID",
                "value": 1500
            }, {
                "id": "US-IL",
                "value": 1500
            }, {
                "id": "US-IN",
                "value": 1500
            }, {
                "id": "US-IA",
                "value": 1500
            }, {
                "id": "US-KS",
                "value": 1500
            }, {
                "id": "US-KY",
                "value": 1500
            }, {
                "id": "US-LA",
                "value": 1500
            }, {
                "id": "US-ME",
                "value": 1500
            }, {
                "id": "US-MD",
                "value": 1500
            }, {
                "id": "US-MA",
                "value": 1500
            }, {
                "id": "US-MI",
                "value": 1500
            }, {
                "id": "US-MN",
                "value": 1500
            }, {
                "id": "US-MS",
                "value": 1500
            }, {
                "id": "US-MO",
                "value": 1500
            }, {
                "id": "US-MT",
                "value": 1500
            }, {
                "id": "US-NE",
                "value": 1500
            }, {
                "id": "US-NV",
                "value": 1500
            }, {
                "id": "US-NH",
                "value": 1500
            }, {
                "id": "US-NJ",
                "value": 1500
            }, {
                "id": "US-NM",
                "value": 1500
            }, {
                "id": "US-NY",
                "modalUrl": "https://en.wikipedia.org/wiki/China",
                "selectable": true,
                "value": 1
            }, {
                "id": "US-NC",
                "value": 1500
            }, {
                "id": "US-ND",
                "value": 1500
            }, {
                "id": "US-OH",
                "value": 1500
            }, {
                "id": "US-OK",
                "value": 1500
            }, {
                "id": "US-OR",
                "value": 1500
            }, {
                "id": "US-PA",
                "value": 1500
            }, {
                "id": "US-RI",
                "value": 1500
            }, {
                "id": "US-SC",
                "value": 1500
            }, {
                "id": "US-SD",
                "value": 1500
            }, {
                "id": "US-TN",
                "value": 1500
            }, {
                "id": "US-TX",
                "value": 1500
            }, {
                "id": "US-UT",
                "value": 1500
            }, {
                "id": "US-VT",
                "value": 1500
            }, {
                "id": "US-VA",
                "value": 1500
            }, {
                "id": "US-WA",
                "value": 1500
            }, {
                "id": "US-WV",
                "value": 1500
            }, {
                "id": "US-WI",
                "value": 1500
            }, {
                "id": "US-WY",
                "value": 1500
            }]
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
                    'height': '80%'
                });
            }
        }]
    });

</script>
<!--
<script>
var map = AmCharts.makeChart( "chartdiv", {
  "type": "map",
  "theme": "light",
  "colorSteps": 20,

  "dataProvider": {
    "map": "usaLow",
    "areas": [ {
      "id": "US-AL",
      "modalUrl": "https://en.wikipedia.org/wiki/United_States",
      "selectable": true,
      "value": 16932
    }, {
      "id": "US-AK",
      "value": 99626932
    }, {
      "id": "US-AZ",
      "value": 5130632
    }, {
      "id": "US-AR",
      "value": 2673400
    }, {
      "id": "US-CA",
      "value": 33871648
    }, {
      "id": "US-CO",
      "value": 4301261
    }, {
      "id": "US-CT",
      "value": 3405565
    }, {
      "id": "US-DE",
      "value": 783600
    }, {
      "id": "US-FL",
      "value": 15982378
    }, {
      "id": "US-GA",
      "value": 8186453
    }, {
      "id": "US-HI",
      "value": 1211537
    }, {
      "id": "US-ID",
      "value": 1293953
    }, {
      "id": "US-IL",
      "value": 12419293
    }, {
      "id": "US-IN",
      "value": 6080485
    }, {
      "id": "US-IA",
      "value": 2926324
    }, {
      "id": "US-KS",
      "value": 2688418
    }, {
      "id": "US-KY",
      "value": 4041769
    }, {
      "id": "US-LA",
      "value": 4468976
    }, {
      "id": "US-ME",
      "value": 1274923
    }, {
      "id": "US-MD",
      "value": 5296486
    }, {
      "id": "US-MA",
      "value": 6349097
    }, {
      "id": "US-MI",
      "value": 9938444
    }, {
      "id": "US-MN",
      "value": 4919479
    }, {
      "id": "US-MS",
      "value": 2844658
    }, {
      "id": "US-MO",
      "value": 5595211
    }, {
      "id": "US-MT",
      "value": 902195
    }, {
      "id": "US-NE",
      "value": 1711263
    }, {
      "id": "US-NV",
      "value": 1998257
    }, {
      "id": "US-NH",
      "value": 1235786
    }, {
      "id": "US-NJ",
      "value": 8414350
    }, {
      "id": "US-NM",
      "value": 1819046
    }, {
      "id": "US-NY",
      "value": 18976457
    }, {
      "id": "US-NC",
      "value": 8049313
    }, {
      "id": "US-ND",
      "value": 642200
    }, {
      "id": "US-OH",
      "value": 11353140
    }, {
      "id": "US-OK",
      "value": 3450654
    }, {
      "id": "US-OR",
      "value": 3421399
    }, {
      "id": "US-PA",
      "value": 12281054
    }, {
      "id": "US-RI",
      "value": 1048319
    }, {
      "id": "US-SC",
      "value": 4012012
    }, {
      "id": "US-SD",
      "value": 754844
    }, {
      "id": "US-TN",
      "value": 5689283
    }, {
      "id": "US-TX",
      "value": 20851820
    }, {
      "id": "US-UT",
      "value": 2233169
    }, {
      "id": "US-VT",
      "value": 608827
    }, {
      "id": "US-VA",
      "value": 7078515
    }, {
      "id": "US-WA",
      "value": 5894121
    }, {
      "id": "US-WV",
      "value": 1808344
    }, {
      "id": "US-WI",
      "value": 5363675
    }, {
      "id": "US-WY",
      "value": 493782
    } ]
  },

  "areasSettings": {
    "autoZoom": true,
    "selectedColor": "#CC0000"
  },

  "valueLegend": {
    "right": 10,
    "minValue": "little",
    "maxValue": "a lot!"
  },
  "listeners": [{
    "event": "clickMapObject",
    "method": function(event) {
      $.fancybox({
        "href": event.mapObject.modalUrl,
        "title": event.mapObject.title,
        "type": "iframe"
      });
    }
  }],

  "export": {
    "enabled": true
  }

} );</script>-->
