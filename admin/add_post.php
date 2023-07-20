<?php
    include "includes/head.php";

    $alert = "";
    if(!empty('img')) {
    function valid_image(){
        global $alert;
        $name = $_FILES['img']['name'];
        $size = $_FILES['img']['size'];
        $tmp_name = $_FILES['img']['tmp_name'];
        $valid_ext = ["jpg","jpeg","png","webp"];
        $ext = pathinfo($name,PATHINFO_EXTENSION);
        if(in_array($ext,$valid_ext)){
            if($size > 2097152){
                $alert = "<div class='alert alert-danger'>Afbeeldingsgrootte van meer dan 2mb is niet toegestaan. </div>";
            }else{
                if(move_uploaded_file($tmp_name,"../images/$name")){
                    return true;
                }else{
                    $alert = "<div class='alert alert-danger'>Afbeelding uploaden mislukt. </div>";
                }
            }
        }else{
            $alert = "<div class='alert alert-danger'>Afbeelding is ongeldig. Alleen (jpg,jpeg,png,webp) wordt ondersteund. </div>";
        }
    }

    if(isset($_POST['save']) && !empty($_POST['title']) && !empty($_POST['desc'])&& !empty($_POST['category']) && !empty($_POST['videolink']) && !empty($_FILES['img']['name'])){
        $title = $_POST['title'];
        $desc = $_POST['desc'];
        $category = $_POST['category'];
        $date = date("Y-m-d");
        $videolink = $_POST['videolink'];
        $name = $_FILES['img']['name'];
        $author = $_SESSION['id'];
        if(valid_image()){
            $sql = "INSERT INTO posts(ptitle,pdesc,date,pvideo,pimage,pcat,pauthor) VALUES('$title','$desc','$date',NULL,'$name',$category,$author);UPDATE category SET post_under_cat = post_under_cat + 1 WHERE cid = $category;";
            $ans = mysqli_multi_query($conn,$sql);
            if($err = mysqli_error($conn)){die($err);}
            if($ans){
                echo("<script>location.href = 'post.php';</script>");
            }else{
                $alert = "<div class='alert alert-danger'>afbeelding is geüpload maar gegevens zijn niet ingevoegd. </div>";
            }
        }
      } else {

        if(isset($_POST['save']) && !empty($_POST['title']) && !empty($_POST['desc']) && !empty($_POST['category']) && !empty($_POST['videolink'])){
          $title = $_POST['title'];
          $desc = $_POST['desc'];
          $category = $_POST['category'];
          $date = date("Y-m-d");
          $videolink = $_POST['videolink'];
          $author = $_SESSION['id'];
          $sql = "INSERT INTO posts(ptitle,pdesc,date,pvideo,pimage,pcat,pauthor) VALUES('$title','$desc','$date','$videolink',NULL,$category,$author);UPDATE category SET post_under_cat = post_under_cat + 1 WHERE cid = $category;";
          $ans = mysqli_multi_query($conn,$sql);
          if($err = mysqli_error($conn)){die($err);}
          if($ans){
              echo("<script>location.href = 'post.php';</script>");
          }else{
              $alert = "<div class='alert alert-danger'>videolink gegevens zijn niet ingevoegd. </div>";
          }
        }
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
      <h2>Bericht Toevoegen</h2>
      <div class="right-wrapper text-end">
        <ol class="breadcrumbs">
          <li>
            <a href="dashboard.php"><i class="bx bx-home-alt"></i></a>
          </li>
          <li><span>Nieuws</span></li>
          <li><a href="post.php"></a><span>Berichten Overzicht</span></li>
        </ol>
        <a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fas fa-chevron-left"></i></a>
      </div>
    </header>
    <!-- start: page -->
      <div class="row">


        <div class="row justify-content-center">
            <div class="col-6 text-center"><?php echo $alert; ?></div>
        </div>
        <form autocomplete="off" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="w-50 m-auto bg-light p-3 text-capitalize" method="post" enctype="multipart/form-data">
            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">Titel</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="title" required>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">Beschrijving</label>
                <div class="col-sm-10">
                    <textarea name="desc" id="desc" class="form-control" cols="30" rows="10" ></textarea>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">Videolink</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="videolink" <?php echo !empty('img') ? 'required':NULL; ?>>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">Miniatuur</label>
                <div class="col-sm-10">
                    <input type="file" class="form-control" name="img">
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
                                echo "<option selected disabled>Selecteer categorie.</option>";
                                while($row = mysqli_fetch_assoc($query)){
                                    echo "<option value='{$row['cid']}'>{$row['cname']}</option>";
                                }
                            }else{
                                echo "<option selected disabled>Geen Categorie gevonden.</option>";
                            }
                          ?>
                    </select>
                </div>
            </div>
            <input type="submit" class="btn btn-dark d-block" value="Opslaan" name="save">
        </form>
    </div>
</body>
</html>
