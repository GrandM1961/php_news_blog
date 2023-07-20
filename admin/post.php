<?php
include "includes/head.php";
include "includes/header.php";
?>
<!-- end: header -->
<div class="inner-wrapper">
  <!-- start: sidebar -->
  <?php include('includes/sidebarlinks.php'); ?>
  <!-- end: sidebar -->
  <section role="main" class="content-body">
    <header class="page-header">
      <h2>Berichten Overzicht</h2>
      <div class="right-wrapper text-end">
        <ol class="breadcrumbs">
          <li><a href="dashboard.php"><i class="bx bx-home-alt"></i></a></li>
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
              <h2 class="card-title text-capitalize p-3">Alle berichten</h2>
              <div class="col-md-3"><a href="add_post.php" class="btn btn-default">Bericht Toevoegen</a></div>
            </header>
            <div class="card-body">
              <table class="table table-bordered table-striped mb-0" id="datatable-default">
                <thead>
                  <tr>
                      <th>#</th>
                      <th>Afbeelding</th>
                      <th>Videolink</th>
                      <th>Titel</th>
                      <th>Beschrijving</th>
                      <th>Datum</th>
                      <th>Categorie</th>
                      <th>Auteur</th>
                      <th>Acties</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                        $owner = $_SESSION['id'];
                        if($_SESSION['role'] == 1){
                            $x = "";
                        }else{
                            $x = "WHERE pauthor = $owner";
                        }
                        $limit = 10;
                        $page = (!empty($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page']:1;
                        $offset = ($page - 1) * $limit;
                        $query = "SELECT * FROM posts p JOIN category c ON p.pcat = c.cid JOIN users u ON p.pauthor = u.uid $x ORDER BY pid DESC LIMIT $offset,$limit";
                        $result = mysqli_query($conn,$query);
                        if($err = mysqli_error($conn)){
                            die($err);
                        }else{
                            if(mysqli_num_rows($result) > 0){
                                while($rows = mysqli_fetch_assoc($result)){
                                    $t = substr($rows['ptitle'],0,50);
                                    $d = substr($rows['pdesc'],0,100);
                                    if(empty($rows['pvideo'])){
                                    echo "<tr>
                                    <td>{$rows['pid']}</td>
                                    <td><img src='../images/{$rows['pimage']}' class='rounded' height='100' width='170'></td>
                                    <td>{$rows['pvideo']}</td>
                                    <td>$t...</td>
                                    <td>$d...</td>
                                    <td>{$rows['date']}</td>
                                    <td>{$rows['cname']}</td>
                                    <td>{$rows['fname']} {$rows['lname']}</td>
                                    <td><a href='edit_post.php?pid={$rows['pid']}' class='btn btn-success mx-2 text-capitalize'><i class='far fa-edit'></i> Bewerken</a><a href='delete_post.php?pid={$rows['pid']}&cid={$rows['pcat']}&img={$rows['pimage']}&img={$rows['pvideo']}' class='btn btn-danger text-capitalize'><i class='far fa-trash'></i> Verwijderen</a></td>
                                </tr><tr></tr>";
                              }
                                if(empty($rows['pimage'])){
                                echo "<tr>
                                <td>{$rows['pid']}</td>
                                <td><embed src='{$rows['pvideo']}' height='100' width='170'></td>
                                <td>{$rows['pvideo']}</td>
                                <td>$t...</td>
                                <td>$d...</td>
                                <td>{$rows['date']}</td>
                                <td>{$rows['cname']}</td>
                                <td>{$rows['fname']} {$rows['lname']}</td>
                                <td><a href='edit_post1.php?pid={$rows['pid']}' class='btn btn-success mx-2 text-capitalize'><i class='far fa-edit'></i> Bewerken</a><a href='delete_post.php?pid={$rows['pid']}&cid={$rows['pcat']}&img={$rows['pimage']}&img={$rows['pvideo']}' class='btn btn-danger text-capitalize'><i class='far fa-trash'></i> Verwijderen</a></td>
                            </tr><tr></tr>";
                          }
                          else{
                            if(empty($rows['pvideo'])){  if(empty($rows['pimage'])){
                            echo "<tr><td colspan='9'>Geen gegevens gevonden.</td></tr>";
                            }
                          }
                        }
                      }
                    }
                  }
                  ?>
                </tbody>
              </table>
              <ul class="pagination float-end">
                <?php
                    $r = mysqli_query($conn,"SELECT COUNT(uid) as total FROM posts p JOIN category c ON p.pcat = c.cid JOIN users u ON p.pauthor = u.uid");
                    if($err = mysqli_error($conn)){die($err);}
                    if(mysqli_num_rows($r) > 0){
                        $res = mysqli_fetch_assoc($r);
                        $total = $res['total'];
                        $total_page = ceil($total / $limit);
                        if($page > 1){
                            echo "<li class='page-item'><a class='page-link' href='post.php?page=".($page - 1)."'>Vorige</a></li>";
                        }
                        for($i=1;$i <= $total_page;$i++){
                            $a = ($page == $i)? "bg-dark text-white":"";
                            echo "<li class='page-item'><a class='page-link $a' href='post.php?page=$i'>$i</a></li>";
                        }
                        if($page < $total_page){
                            echo "<li class='page-item'><a class='page-link' href='post.php?page=".($page + 1)."'>Volgende</a></li>";
                        }
                    }else{
                        die("Er ging iets fout.");
                    }
                ?>
              </ul>
            </div>
          </section>
        </div>
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
