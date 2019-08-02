<?php

session_start();

if(isset($_SESSION['election_id']) && isset($_SESSION['nomination_ids']) && isset($_SESSION['post_titles']) && $_SESSION['post_total_candidates'] && $_SESSION['total_posts']){

  $election_id = $_SESSION['election_id'];
  $total_posts = $_SESSION['total_posts'];
  $nomination_ids= $_SESSION['nomination_ids'];
  $post_titles = $_SESSION['post_titles'];
  $post_candidates = $_SESSION['post_total_candidates'];

}

include("db.php");

?>

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

          <br><h1>Create Election</h1>

          <form method="post">

                <?php

                for($i=0 ; $i<$total_posts ; $i++){

                  echo'
                  <fieldset>
                  <legend>'.$post_titles[$i].'</legend>';

                    for($j=0 ; $j<$post_candidates[$i] ; $j++){
                    
                      echo'
                      <div class="row">
                        <div class="col-md-12">
                          <input type="text" class="form-control" placeholder="Enter canidate name ' . ($j+1) . '" name="candidate_name' . $i . '_' . $j.'">
                        </div>
                        </div>';
                    
                    }
                    
                    echo '</fieldset><br>';
                }

                ?>

            <div class="row">
              <div class="col-md-12">
                <button type="submit" class="btn btn-primary btn-lg btn-block btn btn-success" name="next_button_voters">Next</button>
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

if(isset($_POST['next_button_voters'])){

  $candidates_query = "INSERT INTO candidate(candidate_name, nomination_id) VALUES";

  for($i=0 ; $i<$total_posts ; $i++){

    for($j=0 ; $j<$post_candidates[$i] ; $j++){

      $candidate_name = $_POST['candidate_name'.($i).'_'.($j)];
      $candidates_query .= '("' . $candidate_name . '",' . $nomination_ids[$i] . '),';

    }

  }

  $error = mysqli_error($onevote_db);
  echo $error;
  
  if(mysqli_query($onevote_db, rtrim($candidates_query,','))){
    echo "<script>window.location = 'http://localhost/onevote/voters.php'</script>";
  }
  else {
    echo "<script>alert('Error in adding candidates.')</script>";
  }

  $error = mysqli_error($onevote_db);
  echo $error;
}

?>