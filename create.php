<?php session_start(); ?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>OneVote - Create Election</title>

    <link rel="icon" href="img/icon.png">
 
  </head>
  
  <body>

    <div class="container-fluid">
      <div class="row">
        
        <div class="col-md-1"></div>
        
        <div class="col-md-10">

          <br><br><br>
          <p style="text-align: center"><img src="img/logo.png" class="center-block"></p>

          <form method="post">

            <fieldset>
              <legend>Basic Details</legend>
              
              <div class="row">
                <div class="col-md-5">
                  <label for="election_title">Election Title</label>
                </div>
                <div class="col-md-3">
                  <label for="election_date">Election Date</label>
                </div>
                <div class="col-md-2">
                  <label for="election_time">Election Time</label>
                </div>
                <div class="col-md-2">
                  <label for="election_time">Election Duration</label>
                </div>
              </div>

              <div class="row">
                <div class="col-md-5">
                  <input type="text" class="form-control" placeholder="Enter title" name="election_title">
                </div>
                <div class="col-md-3">
                  <input type="date" class="form-control" placeholder="Election Date" name="election_date">
                </div>
                <div class="col-2">
                  <input type="time" class="form-control" placeholder="Election Time" name="election_time">
                </div>
                <div class="col-2">
                  <input type="number" class="form-control" placeholder="Enter duration" name="election_duration">
                </div>
              </div>

              <br>

              <div class="row">
                <div class="col-md-6">
                  <label for="election_title">Total Posts</label>
                </div>
                <div class="col-md-6">
                  <label for="election_date">Total Voters</label>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <input type="number" class="form-control" placeholder="Enter total posts count" name="total_posts">
                </div>
                <div class="col-md-6">
                  <input type="number" class="form-control" placeholder="Enter total voters count" name="total_voters">
              </div>

            </fieldset><br>

            <div class="row">
              <div class="col-md-12">
                <button type="submit" class="btn btn-primary btn-lg btn-block btn btn-success" name="next_button_posts">Next</button>
              </div>
            </div>

          </form>
       
        </div>

        <div class="col-md-1"></div>

      </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  
  </body>
</html>

<?php

include("db.php");

if(isset($_POST['next_button_posts'])){
  
  $election_title = $_POST['election_title'];
  $election_date = $_POST['election_date'];
  $election_time = $_POST['election_time'];
  $election_duration = $_POST['election_duration'];
  $total_posts = $_POST['total_posts'];
  $total_voters = $_POST['total_voters'];

  //election basic details
  $election_details_query = "

      INSERT INTO election (election_title, election_date, election_time, election_duration, total_posts, total_voters)
      VALUES ('$election_title','$election_date', '$election_time', '$election_duration', '$total_posts', '$total_voters')
  ";

  $error = mysqli_error($onevote_db);
  echo $error;
  
  if(mysqli_query($onevote_db, $election_details_query)){
    
    $election_id = mysqli_insert_id($onevote_db);
    //echo $election_id;

    $_SESSION['election_id'] = $election_id;
    $_SESSION['total_posts'] = $total_posts;
    $_SESSION['total_voters'] = $total_voters;
    echo "<script>window.location = 'http://localhost/onevote/posts.php'</script>";
  }
  else {
    echo "<script>alert('Error in adding basic details.')</script>";
  }

  $error = mysqli_error($onevote_db);
  echo $error;

}

?>