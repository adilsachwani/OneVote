<?php

session_start();

if(isset($_SESSION['election_id']) && isset($_SESSION['total_voters'])){
  $election_id = $_SESSION['election_id'];
  $total_voters = $_SESSION['total_voters'];
  $total_posts = $_SESSION['total_posts'];
}

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
              <legend>Voters</legend>
              
                <?php

                for($i=1 ; $i<=$total_voters ; $i++){
                  
                  echo'
                  <div class="row">
                    <div class="col-md-4">
                      <input type="text" class="form-control" placeholder="Enter voter name '.$i.'" name="voter_name'.$i.'">
                    </div>
                    <div class="col-md-4">
                      <input type="text" class="form-control" placeholder="Enter voter email '.$i.'" name="voter_email'.$i.'">
                    </div>
                    <div class="col-md-4">
                      <input type="text" class="form-control" placeholder="Enter voter public key '.$i.'" name="voter_public_key'.$i.'">
                    </div>
                  </div>';
                
                }

                ?>

            </fieldset><br>

            <div class="row">
              <div class="col-md-12">
                <button type="submit" class="btn btn-primary btn-lg btn-block btn btn-success" name="next_button_tokens" >Submit</button>
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

if(isset($_POST['next_button_tokens'])){

  $voters_query = "INSERT INTO voter(voter_name, voter_email, voter_public_key, election_id) VALUES";

  for($v=1; $v<=$total_voters; $v++){

    $voter_name = $_POST['voter_name'.$v];
    $voter_email = $_POST['voter_email'.$v];
    $voter_public_key = $_POST['voter_public_key'.$v];

    $voters_query .= '("' . $voter_name . '","' . $voter_email . '","' . strtoupper($voter_public_key) . '",' . $election_id . '),';

  }

  $error = mysqli_error($onevote_db);
  echo $error;
  
  if(mysqli_query($onevote_db, rtrim($voters_query,','))){

    $basic_details = mysqli_query($onevote_db, "SELECT * FROM election WHERE election_id = '$election_id'");
    $get_basic_details = mysqli_fetch_assoc($basic_details);
    
    $election_title = $get_basic_details['election_title'];
    $election_date = $get_basic_details['election_date'];
    $election_time = $get_basic_details['election_time'];
    $election_duration = $get_basic_details['election_duration'];
    $election_total_posts = $get_basic_details['total_posts'];
    $election_total_voters = $get_basic_details['total_voters'];
    
    $election_contract = '
    pragma solidity >=0.4.22 <0.6.0;
    pragma experimental ABIEncoderV2;
    
    contract Election {
      
      uint public election_id = '.$election_id.';
      string public election_name = "'.$election_title.'";
      string public election_date = "'.$election_date.'";
      string public election_time = "'.$election_time.'";
      int public election_duration = '.$election_duration.';
      uint public total_posts = '.$election_total_posts.';
      int public total_voters = '.$election_total_voters.';

      uint public candidatesCount = 0;
      uint public postsCount = 0;
      uint public votersCount = 0;
      
      //Model the candidate
      struct Candidate{
        uint cadidateId;  
        string name;
        uint voteCount;
        uint postId;
      }

      //Model the voter
      struct Voter{
        string name;
        string email;
        string public_key;
        uint[' . $total_posts . '] vote;
        bool hasVoted;
      }
      
      //Posts List
      string[] public posts;
      
      //Candidates List
      Candidate[] public candidates;

      //Voters List
      Voter[] public voters;
      
      function addPost(string memory _name) private {
        postsCount++;
        posts.push(_name);
      }
      
      function addCandidate(uint _candidate_id, string memory _name, uint _postId) private {
        candidatesCount++;
        Candidate memory c = Candidate(_candidate_id, _name , 0, _postId);
        candidates.push(c);
      }

      function addVoter(string memory _name, string memory _email, string memory _public_key) private {
        uint[' . $total_posts . '] memory votes;
        votersCount++;
        Voter memory v = Voter(_name , _email, _public_key, votes, false);
        voters.push(v);
      }

       function getPosts() public view returns(string[] memory){
        return posts;
      }

      function getCandidates() public view returns(Candidate[] memory){
        return candidates;
      }

      function getVoters() public view returns(Voter[] memory){
        return voters;
      }

      function castVote(uint postId, uint candidateId, uint voterId) public payable {
         
         if(voters[voterId].hasVoted == false){
          voters[voterId].vote[postId] = candidateId;
          candidates[candidateId].voteCount++;
          
          if(postId == (total_posts - 1)){
          voters[voterId].hasVoted = true;
          }
         }
      }

      constructor() public {

    ';

    $post_query = "

        SELECT p.post_id, p.post_title
        FROM post p
        INNER JOIN nomination n
        ON p.post_id = n.post_id
        WHERE n.election_id = '$election_id'

    ";
    
    $post_details = mysqli_query($onevote_db, $post_query);

    $p = 0;
    $cid = 0;

    while($post = mysqli_fetch_array($post_details)){

      $post_id = $post["post_id"];
      $post_title = $post["post_title"];

      $election_contract .= 'addPost("' . $post_title . '");';

      $candidate_query = "

        SELECT c.candidate_name
        FROM candidate c
        INNER JOIN nomination n
        ON c.nomination_id = n.nomination_id
        WHERE n.election_id = '$election_id' AND n.post_id = '$post_id'

    ";
      
      $candidate_details = mysqli_query($onevote_db, $candidate_query);

      while($candidate = mysqli_fetch_array($candidate_details)){
        $election_contract .= 'addCandidate(' . $cid . ',"' . $candidate['candidate_name'] . '",' . $p . ');';
        $cid++;
      }

      $p++;

    }

    $voter_query = "

        SELECT *
        FROM voter
        WHERE election_id = '$election_id'

    ";

    $voter_details = mysqli_query($onevote_db, $voter_query);

      while($voter = mysqli_fetch_array($voter_details)){

        $voter_name = $voter['voter_name'];
        $voter_email = $voter['voter_email'];
        $voter_public_key = $voter['voter_public_key'];
        
        $election_contract .= 'addVoter("'. $voter_name . '","' . $voter_email . '","'. $voter_public_key . '");';
      
      }

    $election_contract .= "
        }
      }";

    $handler = fopen('Election.sol', 'w');
    fwrite($handler, $election_contract);
    echo "<script>window.location = 'http://localhost/onevote/deploy.php'</script>";


  } else {
    echo "<script>alert('Error in adding voters.')</script>";
  }

  $error = mysqli_error($onevote_db);
  echo $error;
}

?>