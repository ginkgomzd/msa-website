<?php /* Template Name: Detailed view */ ?>
<?php get_header(); ?>

<!--<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">-->

<div class="container">
    <div class="row">
        <!--       main  content -->
        <div class="col-md-9">
            <section id="main-info">
                <h3>Title</h3>
                <p>
                    <span><strong>Sponsors:</strong></span>
                    <span><a href="">test</a></span>
                </p>
                <p>
                    <span><strong>Session:</strong></span>
                    <span>2015 - 2016</span>
                </p>
                <p>
                    <span><strong>Title:</strong></span>
                    <span>Lorem ipsum dolor sit amet.</span>
                </p>
                <p>
                    <span><strong>Abstract:</strong></span>
                    <span>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Rerum, voluptate soluta vel, facere quam, natus ipsam excepturi eius magnam fugiat voluptatum eligendi. Magnam aspernatur, tenetur. Nobis autem, sapiente delectus porro.</span>
                </p>
                <p>
                    <span><strong>Categorie(s):</strong></span>
                    <span>2015 - 2016</span>
                </p>
                <p>
                    <span><strong>Keyword(s):</strong></span>
                    <span>2015 - 2016</span>
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
                    <div class="progress-bar bg-success " role="progressbar" style="width:10%; height:40px">
                        1
                    </div>
                    <div class="progress-bar progress-bar-warning" role="progressbar" style="width:10%; height:40px">
                        2
                    </div>
                    <div class="progress-bar progress-bar-danger" role="progressbar" style="width:10%; height:40px">
                        3
                    </div>
                    <div class="progress-bar progress-bar-danger" role="progressbar" style="width:10%; height:40px">
                        4
                    </div>
                    <div class="progress-bar progress-bar-danger" role="progressbar" style="width:10%; height:40px">
                        5
                    </div>
                    <div class="progress-bar progress-bar-danger" role="progressbar" style="width:10%; height:40px">
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
                <a href="#" class="btn btn-info">View Full Bill Text</a>
            </div>
            <br>
            <section id="Additional-info">
                <h5>Additional Session Info</h5>
                <br>
                <p>
                    <span><strong>State:</strong></span>
                    <span>test</span>
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
<?php get_footer() ?>

<script>
    $(document).ready(function() {
        
        var x = '<textarea rows="4" cols="50" name="note1"></textarea>';
        
        $("#addNote").click(function() {
            $( ".notes" ).append(x);
        });


    });

</script>
