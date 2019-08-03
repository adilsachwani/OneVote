<?php

session_start();
if(isset($_SESSION['election_id']) && isset($_SESSION['total_voters'])){
  $election_id = $_SESSION['election_id'];
  $total_voters = $_SESSION['total_voters'];
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

  <script type="text/javascript" src="web3.min.js"></script>

</head>

<body>

  <div class="container-fluid">
    <div class="row">

      <div class="col-md-3"></div>
      
      <div class="col-md-6"><br>

        <br><br><br>
        <p style="text-align: center"><img src="img/logo.png" class="center-block"></p><br>

        <h2 id="promptElection" style="text-align: center">Do you really want to deploy the election?</h2>
        <h2 id="congratulations" class="text-success" style="text-align: center"></h2><br>
        <p id="transaction" style="text-align: center" class="transactionText"></p>
        
        <div id="buttons">
          <button type="submit" class="btn btn-primary btn-lg btn-block btn btn-success" onclick="userActionSubmit()">Yes</button>
          <button type="submit" class="btn btn-primary btn-lg btn-block btn btn-danger" onclick="userActionCancel()">No</button>
        </div>

        </div>

      </div>

      <div class="col-md-3"></div>

    </div>
  </div>

  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>
</html>


<script>

  const api_link = "http://localhost:3000/";

  const Web3 = require('web3');
  var web3;
  var web3Provider = null;
  var public_address;
  var private_key = "08430C56D97E9D5F87C362CE53B3E1F512F40D83DB61AE28ECF8C7F63032F72D";

  const connectWithWeb3 = async() => {

    if (typeof web3 !== 'undefined') {
      // If a web3 instance is already provided by Meta Mask.
      web3Provider = web3.currentProvider;
      web3 = new Web3(web3.currentProvider);
      console.log("Metamask connected");
    } else {
      // Specify default instance if no web3 instance provided
      web3Provider = new Web3.providers.HttpProvider('http://localhost:7545');
      web3 = new Web3(web3Provider);
      console.log("ganache connected");
    }

    getPublicAddress();

  }

  const getPublicAddress = async() => {

        web3.eth.getCoinbase(function(err, account) {
        if(err === null){
          public_address = account.toUpperCase();
          console.log(account);
        }
        else{
          console.log(err);
        }
      })
  }

  const userActionSubmit = async () => {
      
      document.getElementById("buttons").style.display = "none";
      document.getElementById("promptElection").style.display = "none";

      var election_id = "<?php echo $election_id; ?>";
      
      var url = api_link + "deploy_contract/" + public_address + "/" + private_key;

      const response = await fetch(url);
      const myJson = await response.json();
      console.log(myJson);
      document.getElementById("congratulations").innerHTML = "Congratulations your Election is deployed!";
      document.getElementById("transaction").innerHTML = "<kbd>" + myJson.transaction_hash + "</kbd>"

      url = api_link + "send_election_transaction_mail/" + myJson.transaction_hash + "/" + election_id;
      response = await fetch(url);
  
  }

  const userActionCancel = async() => {
    
    window.location.href = 'index.php';
  
  }

  window.onload = connectWithWeb3();

</script>