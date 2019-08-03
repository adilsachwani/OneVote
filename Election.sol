
    pragma solidity >=0.4.22 <0.6.0;
    pragma experimental ABIEncoderV2;
    
    contract Election {
      
      uint public election_id = 29;
      string public election_name = "Animation Awards";
      string public election_date = "2019-12-31";
      string public election_time = "13:59:00";
      int public election_duration = 3;
      uint public total_posts = 3;
      int public total_voters = 2;

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
        uint[3] vote;
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
        uint[3] memory votes;
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

    addPost("Best Hindi Movie");addCandidate(0,"3 Bahadur",0);addCandidate(1,"Chotta Bheen",0);addCandidate(2,"Allahyar",0);addPost("Best English Movie");addCandidate(3,"Toy Story 3",1);addCandidate(4,"Rango",1);addCandidate(5,"Inside Out",1);addPost("Best Cartoon Series");addCandidate(6,"Ed Edd and Eddy",2);addCandidate(7,"Fosters Home",2);addCandidate(8,"Jonny Bravo",2);addVoter("Adil Aslam","adilsachwani@gmail.com","0X6F49FAC2422D4F8A27BC799FF83CD8A77961A493");addVoter("Naveed Raza","naveedraza2907@gmail.com","0X0E19AF7E49B8AF629CDD8A51EB61248A677387AC");
        }
      }