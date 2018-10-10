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
                <h3 class="mb-3">
                   <?php if($row->isPriority === '1'){ ?>
                <i class="star fa fa-star" aria-hidden="true"></i>
                <?php } ?>
                   
                   
                    <?php echo $row->state.' '.$row->type .' ' .$row->number ; ?> </h3>

               <hr class="w-25 ml-0">
                <table>
                    <tr>
                        <td>
                          Sponsors  
                        </td>
                        <td>
                          <a href="<?php echo $row->sponsor_url; ?>" target="_blank"><?php echo $row->sponsor_name; ?></a>
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
                          <?php echo getCategories($id) ?>  
                        </td>
                    </tr>
                    <tr>
                        <td>
                          Keyword(s) 
                        </td>
                        <td>
                          <?php echo getKeyword($id) ?> 
                        </td>
                    </tr>
                    <tr>
                        <td>
                          Latest action 
                        </td>
                        <td>
                          2015 - 2016
                        </td>
                    </tr>
                    
                </table>
            </section>
            <br>
            <section id="latest-action">
                <h3>Latest Action</h3>
                
                <?php
                switch (strtolower ($row->status_standard_val)) {
                    case "prefiled":
                        $status = 1;
                        break;
                    case "introduced, first chamber":
                        $status = 2;
                        break;
                    case "assigned to committee, first chamber":
                        $status = 3;
                        break; 
                    case "hearing scheduled":
                        $status = 4;
                        break;
                    case "passed by first committee":
                        $status = 5;
                        break; 
                    case "passed by first house":
                        $status = 6;
                        break; 
                    case "assigned to committee, second chamber":
                        $status = 7;
                        break;  
                    case "passed committee, second chamber":
                        $status = 8;
                        break; 
                    case "passed second chamber":
                        $status = 9;
                        break;
                    case "transmitted to governor":
                        $status = 10;
                        break;
                }         
                ?>
                <div class="progress" style="height:40px">
                   <?php for ($x = 1; $x <= $status; $x++) { ?>
                    <div class="progress-bar" role="progressbar" style="width:10%; height:40px">
                        <?php echo $x; ?>
                    </div>
                    <?php } ?>
                </div>
                <div>
                    <span><strong>Status </strong><?php echo $status .'-'. $row->status_standard_val; ?> </span>
                </div>
            </section>
            <br>
            <section id="notes">
                        <h3 class="d-inline">Notes</h3>
                        <button id="addNote" type="button" class="float-right button gradient-bg  btn-small"><i class="fa fa-plus" aria-hidden="true"></i> Add note</button>
                <div class="notes mt-3">
                    <textarea rows="4" cols="50" name="note1"></textarea>
                </div>
                <button id="sendNote" type="button" class="button gradient-bg">Send</button>
            </section>
        </div>

        <!--       sidebar -->
        <div class="col-md-3">
                <div class="social mb-3">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="far fa-envelope"></i></a>
                </div>
            <div>
                <?php if($row->textUploaded === '1'){ ?>
                <button type="button" class="button gradient-bg  btn-small mb-4 w-100" data-toggle="modal" data-target="#myModal">View Full Bill Text</button>
                <?php } ?>
            </div>
            <br>
            <section id="Additional-info" class="contact-info mb-5">
               <h2>Info</h2>
                <br>
                <h5>Additional Session Info</h5>
                <br>
                <ul class="p-0 m-0">
                        <li>State:<span><?php echo $row->state; ?></span></li>
                        <li>Convenes: <span>01/01/2018</span></li>
                        <li>Adjoums: <span>01/01/2018</span></li>
                        <li>Cary over: <span>No</span></li>
                        <li>Prefilling: <span>No</span></li>
                        <li>Additional info: <span>No2016 regular sesion</span></li>
                    </ul>
                    
                    
                    
                    
            </section>
            <br>
            <section id="Additional-info" class="opening-hours">
                <h2>Hearings</h2>
                <br>
                <div class="hearings">
                    <h5>Hearing title</h5>
                    <ul class="p-0 m-0">
                        <li>Place<span>test</span></li>
                        <li>Date <span>01/01/2018</span></li>
                        <li>Time <span>01:00 PM</span></li>
                        <li>Committee <span>Senate</span></li>
                    </ul>
                    <a href="#" class="button gradient-bg btn-small mt-4 w-100 text-center"><i class="far fa-calendar-alt"></i> Add to calendar</a>


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
        .modal-xl {
            max-width: 1440px;

        }

        .modal-content {
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
                $(".notes").append(x);
            });


        });
    </script>