<?php
      include "includes/head.php";

      $uid = $_GET['uid'];
      if(isset($_POST['update']) && !empty($_POST['fname']) && !empty($_POST['lname']) && !empty($_POST['uname']) && !empty($_POST['uid']) && isset($_POST['role'])){
          $fname = $_POST['fname'];
          $lname = $_POST['lname'];
          $uname = $_POST['uname'];
          $role = $_POST['role'];
          $uid = $_POST['uid'];
          $sql = "UPDATE users SET fname = '$fname',lname = '$lname',uname = '$uname',role = $role WHERE uid = $uid";
          $ans = mysqli_query($conn,$sql);
          if($err = mysqli_error($conn)){die($err);}
          if($ans){
              echo("<script>location.href = 'users.php';</script>");
          }else{
              echo "<div class='alert alert-danger'>Mislukt!</div>";
          }
      }

      $result = mysqli_query($conn,"SELECT * FROM users WHERE uid = $uid");
      if($err = mysqli_error($conn)){
          die($err);
      }else{
          if(mysqli_num_rows($result) == 1){
              $rows = mysqli_fetch_assoc($result);
          }else{
              die("Er ging iets fout.");
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
            <h2>Gebruiker Toevoegen</h2>
            <div class="right-wrapper text-end">
              <ol class="breadcrumbs">
                <li>
                  <a href="dashboard.php"><i class="bx bx-home-alt"></i></a>
                </li>
                <li><span>Gebruikers</span></li>
                <li><a href="users.php"></a><span>Gebruikers Overzicht</span></li>
                <li>><span>Gebruiker Bewerken</span></li>
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
                  <h2 class="card-title text-capitalize p-3">Gebruiker Bewerken</h2>
                </header>
                <div class="card-body">
                  <form autocomplete="off" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="w-50 m-auto bg-light p-3 text-capitalize" method="post">
                      <div class="mb-3 row">
                          <label class="col-sm-2 col-form-label">Voornaam</label>
                          <div class="col-sm-10">
                              <input type="text" class="form-control" name="fname" value="<?php echo $rows['fname']; ?>" required>
                              <input type="hidden" name="uid" value="<?php echo $rows['uid']; ?>">
                          </div>
                      </div>
                      <div class="mb-3 row">
                          <label class="col-sm-2 col-form-label">Achternaam</label>
                          <div class="col-sm-10">
                              <input type="text" class="form-control" name="lname" value="<?php echo $rows['lname']; ?>" required>
                          </div>
                      </div>
                      <div class="mb-3 row">
                          <label class="col-sm-2 col-form-label">Gebruikersnaam</label>
                          <div class="col-sm-10">
                              <input type="text" class="form-control" name="uname" value="<?php echo $rows['uname']; ?>" required>
                          </div>
                      </div>
                      <div class="mb-3 row">
                          <label class="col-sm-2 col-form-label">Rol van Gebruiker</label>
                          <div class="col-sm-10">
                              <select class="form-select" name="role" required>
                                  <?php
                                      if($rows['role'] == 0){
                                          echo "<option value='0' selected>normal user</option>
                                          <option value='1'>Beheerder</option>";
                                      }else{
                                          echo "<option value='0'>Normale Gebruiker</option>
                                          <option value='1' selected>Beheerder</option>";
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
          </div>
        </section>
      </div>
    <?php include('includes/sidebarrechts.php'); ?>
  </section>
  <?php include('includes/scripts.php'); ?>
  <!-- end: page -->
  </body>
</html>
