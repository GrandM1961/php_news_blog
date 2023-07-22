<?php
    include "includes/head.php";
    $alert = "";
    if(!isset($_POST['update']) && (empty($_GET['pid']) || !is_numeric($_GET['pid']))){
        echo("<script>location.href = 'post.php';</script>");
    }
    function process($newvideo){
        global $conn;
        $title = mysqli_real_escape_string($conn,$_POST['title']);
        $desc = mysqli_real_escape_string($conn,$_POST['desc']);
        $newvideo = mysqli_real_escape_string($conn,$_POST['videolink']);
        $category = mysqli_real_escape_string($conn,$_POST['category']);
        $old_cat = mysqli_real_escape_string($conn,$_POST['old_cat']);
        $pid = mysqli_real_escape_string($conn,$_POST['pid']);
        $date = date("Y-m-d");
        $sql = "UPDATE posts SET ptitle = '$title',pdesc = '$desc',date = '$date',pvideo = '$newvideo',pcat = $category WHERE pid = $pid;";
        if($old_cat != $category){
            $sql .= "UPDATE category SET post_under_cat = post_under_cat - 1 WHERE cid = $old_cat;UPDATE category SET post_under_cat = post_under_cat + 1 WHERE cid = $category;";
        }
        $ans = mysqli_multi_query($conn,$sql);
        if($err = mysqli_error($conn)){die($err);}
        if($ans){
            echo("<script>location.href = 'post.php';</script>");
        }else{
            $alert = "<div class='alert alert-danger'>something happen wrong. </div>";
        }
        die();
    }

    if(isset($_POST['update']) && !empty($_POST['title']) && !empty($_POST['desc']) && !empty($_POST['category']) && !empty($_POST['videolink']) && !empty($_POST['old_cat']) && !empty($_POST['pid'])){
        $videolink = mysqli_real_escape_string($conn,$_POST['videolink']);
        if($newvideo['videolink'] != ""){
            valid_video($newvideo);
        }else{
            process($newvideo);
        }
    }


    if($_SESSION['role'] == 1){
      $y = "";
    }else{
      $y = "&& pauthor = {$_SESSION['id']}";
    }
    $pid = mysqli_real_escape_string($conn,$_GET['pid']);
    $result = mysqli_query($conn,"SELECT * FROM posts WHERE pid = $pid $y");
    if($err = mysqli_error($conn)){
        die($err);
    }else{
        if(mysqli_num_rows($result) == 1){
            $row1 = mysqli_fetch_assoc($result);
            $title = $row1['ptitle'];
            $desc = $row1['pdesc'];
            $videolink = $row1['pvideo'];
            $cat = $row1['pcat'];
        }else{
            echo("<script>location.href = 'post.php';</script>");
        }
      }

      include "includes/header.php";
?>
          <!-- end: header -->
          <div class="inner-wrapper">
            <!-- start: sidebar -->
            <?php include('includes/sidebarlinks.php'); ?>
            <!-- end: sidebar -->
            <section role="main" class="content-body">
              <header class="page-header">
                <h2>Video link bewerken</h2>
                <div class="right-wrapper text-end">
                  <ol class="breadcrumbs">
                    <li>
                      <a href="dashboard.php"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li><span><a href="post.php">Nieuws</a></span></li>
                    <li><span>Berichten Overzicht</span></li>
                  </ol>
                  <a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fas fa-chevron-left"></i></a>
                </div>
              </header>
              <!-- start: page -->
              <div class="row">
                <div class="col">
                    <section class="card">
                        <header class="card-header">
                        <div class="card-actions">
                          <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                        </div>
                        <h2 class="card-title text-capitalize p-3">Video link bewerken</h2>
                      </header>
                      <div class="card-body">
                          <div class="row justify-content-center">
                              <div class="col-6 text-center"><?php echo $alert; ?></div>
                          </div>
                            <form autocomplete="off" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="w-50 m-auto bg-light p-3 text-capitalize" method="post" enctype="multipart/form-data">
                                    <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label">Titel</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="title" value="<?php echo $title; ?>" required>
                                        <input type="hidden" name="pid" value="<?php echo $pid; ?>">
                                        <input type="hidden" name="pvideo" value="<?php echo $videolink; ?>">
                                        <input type="hidden" name="old_cat" value="<?php echo $cat; ?>">
                                    </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label class="col-sm-2 col-form-label">Beschrijving</label>
                                        <div class="col-sm-10">
                                            <textarea name="desc" id="desc" class="form-control" cols="30" rows="10" ><?php echo $desc; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label class="col-sm-2 col-form-label">Videolink</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="videolink" value="<?php echo $videolink; ?>" >
                                            </div>
                                    </div>


                                    <div class="mb-3 row">
                                        <label class="col-sm-2 col-form-label">Categorie</label>
                                            <div class="col-sm-10">
                                                <select class="form-select" name="category" required>
                                                    <?php
                                                        $query = mysqli_query($conn,"SELECT * FROM category");
                                                        if($err = mysqli_error($conn)){die($err);}
                                                        if(mysqli_num_rows($query) > 0){
                                                            echo "<option selected disabled>Selecteer Categorie</option>";
                                                            while($row = mysqli_fetch_assoc($query)){
                                                                $se = ($cat == $row['cid'])? "selected":"";
                                                                echo "<option $se value='{$row['cid']}'>{$row['cname']}</option>";
                                                            }
                                                        }else{
                                                            echo "<option selected disabled>Geen Categorie gevonden.</option>";
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                    </div>
                                <input type="submit" class="btn btn-dark d-block" value="Bijwerken" name="update">
                            </form>
                        </div>
                    </section>
                </div>
                <!-- end: page -->
            </section>
            </div>
                <?php include('includes/sidebarrechts.php'); ?>
                </section>
                <?php include('includes/scripts.php'); ?>
                <!-- Examples -->
            </body>
        </html>
