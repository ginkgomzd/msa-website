<?php /* Template Name: Detailed view */ ?>
<?php 
    get_header();
    $id =$_GET["id"];
    $query = "SELECT * FROM `legislation` where id = $id";
    $row=$wpdb->get_row($query,OBJECT);

?>

<div class="container">
    <div class="row">
        <!--       main  content -->
        <div class="col-md-9">
            <section id="main-info">
                <h3><?php echo $row->state.' '.$row->type .' ' .$row->number ; ?>  </h3>
                
                <?php if($row->isPriority === '1'){ ?>
                     <i class="star fa fa-star fa-lg" aria-hidden="true"></i>
                <?php } ?> 
                
                <p>
                    <span><strong>Sponsors:</strong></span>
                    <span><a href="<?php echo $row->sponsor_url; ?>" target="_blank"><?php echo $row->sponsor_name; ?></a></span>
                </p>
                <p>
                    <span><strong>Session:</strong></span>
                    <span><?php echo $row->session; ?></span>
                </p>
                <p>
                    <span><strong>Title:</strong></span>
                    <span><?php echo $row->title; ?></span>
                </p>
                <p>
                    <span><strong>Abstract:</strong></span>
                    <span><?php echo $row->abstract; ?></span>
                </p>
                <p>
                    <span><strong>Categorie(s):</strong></span>
                    <span><?php echo getCategories($id) ?></span>
                </p>
                <p>
                    <span><strong>Keyword(s):</strong></span>
                    <span><?php echo getKeyword($id) ?> </span>
                </p>
                <p>
                    <span><strong>Latest action:</strong></span>
                    <span>2015 - 2016</span>
                </p>
            </section>
            <br>
            <section id="latest-action">
                <h3>Latest Action</h3>
                <div class="progress" style="height:40px">
                    <div class="progress-bar bg-success" role="progressbar" style="width:10%; height:40px">
                        1
                    </div>
                    <div class="progress-bar" role="progressbar" style="width:10%; height:40px">
                        2
                    </div>
                    <div class="progress-bar" role="progressbar" style="width:10%; height:40px">
                        3
                    </div>
                    <div class="progress-bar" role="progressbar" style="width:10%; height:40px">
                        4
                    </div>
                    <div class="progress-bar" role="progressbar" style="width:10%; height:40px">
                        5
                    </div>
                    <div class="progress-bar" role="progressbar" style="width:10%; height:40px">
                        6
                    </div>
                </div>

            </section>
            <br>
            <section id="notes">
                <div class="row">
                    <div class="col-md-9">
                        <h3>Notes</h3>
                    </div>
                    <div class="col-md-3">
                        <button id="addNote" type="button" class="btn btn-primary btn-sm ">Add note</button>
                    </div>
                </div>
                <div class="notes">
                    <textarea rows="4" cols="50" name="note1"></textarea>
                </div>
                    <button id="sendNote" type="button" class="btn btn-primary">Send</button>
            </section>
        </div>

        <!--       sidebar -->
        <div class="col-md-3">
            <div class="row">
                <div class="social">
                    <i class="fab fa-facebook-square fa-2x"></i>
                    <i class="fab fa-twitter fa-2x"></i>
                    <i class="far fa-envelope fa-2x"></i>
                </div>
            </div>
            <br>
            <div>
               <?php if($row->textUploaded === '1'){ ?>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">View Full Bill Text</button>
                <?php } ?>
            </div>
            <br>
            <section id="Additional-info">
                <h5>Additional Session Info</h5>
                <br>
                <p>
                    <span><strong>State:</strong></span>
                    <span><?php echo $row->state; ?></span>
                </p>
                <p>
                    <span><strong>Convenes:</strong></span>
                    <span>01/01/2018</span>
                </p>
                <p>
                    <span><strong>Adjoums:</strong></span>
                    <span>01/01/2018</span>
                </p>
                <p>
                    <span><strong>Cary over:</strong></span>
                    <span>No</span>
                </p>
                <p>
                    <span><strong>Prefilling:</strong></span>
                    <span>No</span>
                </p>
                <p>
                    <span><strong>Additional info:</strong></span>
                    <span>No2016 regular sesion</span>
                </p>
            </section>
            <br>
            <section id="Additional-info">
                <h5>Hearings</h5>
                <br>
                <div class="hearings">
                    <h6>Hearing title</h6>
                    <p>
                        <span><strong>Place:</strong></span>
                        <span>test</span>
                    </p>
                    <p>
                        <span><strong>Date:</strong></span>
                        <span>01/01/2018</span>
                    </p>
                    <p>
                        <span><strong>Time:</strong></span>
                        <span>01:00 PM</span>
                    </p>
                    <p>
                        <span><strong>Committee:</strong></span>
                        <span>Senate </span>
                    </p>
                    <p>
                        <span><a href="#">Add to calendar</a></span>
                        <span><i class="far fa-calendar-alt"></i></span>
                    </p>

                </div>
            </section>
        </div>
    </div>
</div>

<?php
 $txtQuery = "SELECT content FROM `entity_text` where entity_id = $id";
 $row_text=$wpdb->get_row($txtQuery,OBJECT);
?>

<!-- The Modal -->
<style>
    .modal-xl{
        max-width:1440px;
        
    }
    .modal-content{
        padding: 20px;
    }
</style>
<div class="modal fade bd-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <?php echo $row_text->content ?>
    </div>
  </div>
</div>



<?php get_footer() ?>

<script>
    $(document).ready(function() {
        
        var x = '<textarea rows="4" cols="50" name="note1"></textarea>';
        
        $("#addNote").click(function() {
            $( ".notes" ).append(x);
        });


    });

</script>
