<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>dis</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>

<body>
    <?php include "partials/_dbconnect.php";?>
    <?php include "partials/_header.php";?>
    <?php
     $id = $_GET['threadid'];
     $sql= "SELECT * FROM `threads` WHERE thread_id=$id";
     $result = mysqli_query($conn,$sql);
           while($row=mysqli_fetch_assoc($result)){
                $title=$row['thread_title'];
                $desc=$row['thread_desc'];
                $thread_user_id = $row['thread_user_id'];

        // Query the users table to find out the name of OP
                $sql2 = "SELECT email FROM `user` WHERE sno='$thread_user_id'";
                $result2 = mysqli_query($conn, $sql2);
                $row2 = mysqli_fetch_assoc($result2);
                $posted_by = $row2['email'];
           }
     
    ?>

    <?php
    $showAlert= false;
      $method = $_SERVER['REQUEST_METHOD'];
      if($method=='POST'){

        $comment = $_POST['comment'];
        $comment = str_replace("<", "&lt;", $comment);
        $comment = str_replace(">", "&gt;", $comment); 
        $sno = $_POST['sno']; 
        $sql= "INSERT INTO `comments` (`comment_content`, `thread_id`, `comment_by`, `comment_time`) 
        VALUES ('$comment', '$id', '$sno', current_timestamp());";
        $result = mysqli_query($conn,$sql);
        $showAlert = true;
        if($showAlert){
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> Your comment has been added
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>';
        }


      }
    ?>

    <div class="container my-4">
        <div class="jumbotron">
            <h1 class="display-4"><?php echo $title ?></h1>
            <p class="lead"><?php echo $desc ?></p>
            <hr class="my-4">
            <p class="lead">
            <p><b>Posted by:</b> <em><?php echo $posted_by; ?></em></p>
            </p>
        </div>
    </div>

    <?php 
    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==true){ 
    echo '<div class="container">
        <h2>Post a comment</h2>
        <form action= "'. $_SERVER['REQUEST_URI'] . '" method="post">
    <div class="form-group my-4">
        <div class="form-group">
            <label for="exampleFormControlTextarea1">Your Comment</label>
            <textarea class="form-control" id="comment" name="comment" rows="3"></textarea>
            <input type="hidden" name="sno" value="'. $_SESSION["sno"]. '">
        </div>
    </div>
    <button type="submit" class="btn btn-danger">Post</button>
    </form>
    </div>';
    }
    else{
    echo '
    <div class="container">
        <h1 class="py-2">Post a Comment</h1>
        <p class="lead">You are not logged in. Please login to be able to post comments.</p>
    </div>';
    }
    ?>

    <div class="container">
        <h2 class="py-2">Comments</h2>

        <?php

        $cat_id = $_GET['threadid'];
        $sql= "SELECT * FROM `comments` WHERE thread_id=$cat_id";
        $result = mysqli_query($conn,$sql);
        $noResult = true;
           while($row=mysqli_fetch_assoc($result)){
                $noResult = false;
                $id=$row['comment_id'];
                $content=$row['comment_content'];
                $time=$row['comment_time'];
                $thread_user_id = $row['comment_by']; 

                $sql2 = "SELECT email FROM `user` WHERE sno='$thread_user_id'";
                $result2 = mysqli_query($conn, $sql2);
                $row2 = mysqli_fetch_assoc($result2);
                echo '<div class="media my-3">
                <img class="mr-3" src="img/user.jpg" width="54px" alt="">
                <div class="media-body">
                    <p class="font-weight-bold my-0">'. $row2['email']  .' at '. $time .'</p>
                    '. $content .'    
                </div>
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

    <?php 
     include "partials/_footer.php";
    ?>
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