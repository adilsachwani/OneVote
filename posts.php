<?php

session_start();

if(isset($_SESSION['election_id']) && isset($_SESSION['total_posts'])){
  $election_id = $_SESSION['election_id'];
  $total_posts = $_SESSION['total_posts'];
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

            <fieldset>
              <legend>Posts</legend>
              
                <?php

                for($i=1 ; $i<=$total_posts ; $i++){
                  
                  echo'
                  <div class="row">
                    <div class="col-md-10">
                      <input type="text" class="form-control" placeholder="Enter post '.$i.'" name="post_title'.$i.'">
                    </div>
                    <div class="col-md-2">
                      <input type="number" class="form-control" placeholder="Count" name="post_candidates'.$i.'">
                    </div>
                  </div>';
                
                }

                ?>

            </fieldset><br>

            <div class="row">
              <div class="col-md-12">
                <button type="submit" class="btn btn-primary btn-lg btn-block btn btn-success" name="next_button_candidates">Next</button>
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

if(isset($_POST['next_button_candidates'])){

  $nomination_ids = array();
  $post_titles = array();
  $post_total_candidates = array();

  for($p=1; $p<=$total_posts; $p++){

    $post_title = $_POST['post_title'.$p];
    $post_candidates = $_POST['post_candidates'.$p];

    $post_result= mysqli_query($onevote_db, "SELECT post_id FROM post WHERE post_title = '$post_title'");
    $rowcount = mysqli_num_rows($post_result);

    if($rowcount == 1){
      $get_post = mysqli_fetch_assoc($post_result);
      $post_id = $get_post['post_id'];
    }
    else{
      mysqli_query($onevote_db, "INSERT INTO post(post_title) VALUES('$post_title')");
      $post_id = mysqli_insert_id($onevote_db);
    }

    $posts_query = "INSERT INTO nomination(election_id, post_id) VALUES ('$election_id', '$post_id')";
    mysqli_query($onevote_db, $posts_query);
    $nomination_id = mysqli_insert_id($onevote_db);

    array_push($nomination_ids, $nomination_id);
    array_push($post_titles, $post_title);
    array_push($post_total_candidates, $post_candidates);

  }

  $_SESSION['nomination_ids'] = $nomination_ids;
  $_SESSION['post_titles'] = $post_titles;
  $_SESSION['post_total_candidates'] = $post_total_candidates;

  echo "<script>window.location = 'http://localhost/onevote/candidates.php'</script>";

}

?>

