<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>iForum</title>
    <link rel="stylesheet" 
    href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
    integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" 
    crossorigin="anonymous">
    <style>
        #maincontainer{
            min-height: 100vh;
        }
        #bod {
            outline: 2px groove azure;
            padding: 4px;
            border-radius:2px;
        }
    </style>
</head>

<body>
    <?php include "partials/_dbconnect.php";?>
    <?php include "partials/_header.php";?>
    <?php
     $cat_id = $_GET['catid'];
     $sql= "SELECT * FROM `categories` WHERE category_id=$cat_id";
     $result = mysqli_query($conn,$sql);
           while($row=mysqli_fetch_assoc($result)){
                $cat_name=$row['category_name'];
                $cat_desc=$row['category_description'];
           }
     
    ?>

    <?php
      $method = $_SERVER['REQUEST_METHOD'];
      if($method=='POST'){

        $th_title = $_POST['title'];
        $th_desc = $_POST['desc'];

        $th_title = str_replace("<", "&lt;", $th_title);
        $th_title = str_replace(">", "&gt;", $th_title); 

        $th_desc = str_replace("<", "&lt;", $th_desc);
        $th_desc = str_replace(">", "&gt;", $th_desc); 

        $sno = $_POST['sno']; 
        $sql= "INSERT INTO `threads` (`thread_title`, `thread_desc`, `thread_cat_id`, `thread_user_id`, `timestamp`) 
        VALUES ('$th_title', '$th_desc', '$cat_id', '$sno', current_timestamp())";
        $result = mysqli_query($conn,$sql);
        $showAlert = true;
        if($showAlert){
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                     <strong>Success!</strong> Your concern has been submitted, please wait for the community to respond.
                     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
        }


      }
    ?>

    <div class="container my-4">
        <div class="jumbotron">
            <h1 class="display-4">Welcome to <?php echo $cat_name ?></h1>
            <p class="lead"><?php echo $cat_desc ?></p>
            <hr class="my-4">
            <p class="lead">
            <ul>
                <li>No Spam / Advertising / Self-promote in the forums. ...</li>
                <li>Do not post copyright-infringing material. ...</li>
                <li>Do not post “offensive” posts, links or images. ...</li>
                <li>Do not cross post questions. ...</li>
                <li>Do not PM users asking for help. ...</li>
                <li>Remain respectful of other members at all times.</li>
            </ul>
            </p>
        </div>
    </div>
    <?php 
    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==true){ 
    echo '<div class="container">
            <h2>Start a Discussion</h2>
            <form action="'. $_SERVER["REQUEST_URI"] . '" method="post">
        <div class="form-group my-4">
            <label for="exampleInputEmail1">Problem Title</label>
            <input type="text" class="form-control" id="title" name="title" aria-describedby="emailHelp" placeholder="">
            <small id="emailHelp" class="form-text text-muted">Keep Your Title Short.</small>
        </div>
        <input type="hidden" name="sno" value="'. $_SESSION["sno"]. '">
        <div class="form-group my-4">
            <div class="form-group">
                <label for="exampleFormControlTextarea1">Ellaborate your concern</label>
                <textarea class="form-control" id="desc" name="desc" rows="3"></textarea>
            </div>
        </div>
        <button type="submit" class="btn btn-danger">Submit</button>
        </form>
    </div>';
    }
    else{
    echo '
    <div class="container">
        <h1 class="py-2">Start a Discussion</h1>
        <p class="lead">You are not logged in. Please login to be able to start a Discussion</p>
    </div>';
    }
    ?>
    <div class="container">
        <h2 class="py-2">Browse Questions</h2>

        <?php

        $cat_id = $_GET['catid'];
        $sql= "SELECT * FROM `threads` WHERE thread_cat_id=$cat_id";
        $result = mysqli_query($conn,$sql);
        $noResult = true;
           while($row=mysqli_fetch_assoc($result)){
                $noResult = false;
                $id=$row['thread_id'];
                $title=$row['thread_title'];
                $desc=$row['thread_desc'];
                $thread_time = $row['timestamp']; 
                $thread_user_id = $row['thread_user_id']; 
                $sql2 = "SELECT email FROM `user` WHERE sno='$thread_user_id'";
                $result2 = mysqli_query($conn, $sql2);
                $row2 = mysqli_fetch_assoc($result2);
                echo '
                    <div class="media my-3" id="bod">
                        <img src="img/user.jpg" width="54px" class="align-self-center mr-3" alt="...">
                        <div class="media-body">
                            <h5 class="mt-0">'. $title .'</h5>
                            <h6> <b>Asked by:</b> '. $row2['email'] .' <b> at  </b>  '. $thread_time. '</h6>
                            <p>' . $desc . '</p>
                        </div>
                        <a href="thread.php?threadid='. $id .'" class="btn btn-danger">View</a>
                    </div>';
            }
            if($noResult){
                echo '<div class="jumbotron jumbotron-fluid">
                <div class="container">
                  <h1 class="display-4">No Result Found!</h1>
                  <p class="lead">Be The First Person To Ask.</p>
                </div>
              </div>';
            }
        ?>
    </div>
    <?php include "partials/_footer.php"; ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous">
    </script>
</body>

</html>