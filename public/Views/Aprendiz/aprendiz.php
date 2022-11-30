<?php
  include_once "../../Models/connection.php";
  session_start();
  if (isset($_SESSION['id'])) {
    // GET ANNOUNCEMENTS ARRAY
    $announcements = "SELECT asunto, descripcion, fecha, url_portada, url_file, ID_Anuncio, ID_Persona FROM anuncio";
    $announcements_result= mysqli_query($dbConnection, $announcements) or die(mysqli_error($dbConnection));
    $announcements = mysqli_fetch_all($announcements_result, MYSQLI_NUM);
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../img/favicon.ico" />
    <script src="https://kit.fontawesome.com/643b0ccc65.js" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../css/aprendiz.css" />
    <title>Inicio</title>
  </head>
  <body>
    <?php include './sidebar.php' ?>
      <h1 class="main-content__header">Bienvenido aprendiz 👋</h1>
      <!-- BANNER -->
      <div class="banner"><p class="banner__p">¡Skollab es la mejor app para gestionar tu aprendizaje!</p></div>
      <div class="announcements">
        <h2 class="announcements__label">Anuncios y novedades</h2>
        <hr>

        <?php
          if(sizeof($announcements) > 0){
            ?>
            <div class="announcements__container">
              <?php
              for($i=0; $i < sizeof($announcements); $i++){
                $id_owner = $announcements[$i][6];

                // GET ANNOUNCEMENT'S OWNER
                $owner = "SELECT nombres, apellidos FROM persona WHERE ID_Persona = $id_owner";
                $owner_result= mysqli_query($dbConnection, $owner) or die(mysqli_error($dbConnection));
                $owner = mysqli_fetch_all($owner_result, MYSQLI_NUM);
              
                ?>
                <div class="announcement">
                  <div class="announcement__owner">
                    <img class="announcement__owner-photo" src="../img/default.jpeg" alt="owner-photo">
                    <div>
                      <div class="announcement__owner-name"><?php echo $owner[0][0].' '.$owner[0][1] ?></div>
                      <div class="announcement__date">Fecha de publicación: <?php echo $announcements[$i][2] ?></div>
                    </div>
                  </div>
                  <div class="announcement__info">
                    <div class="announcement__title"><?php echo $announcements[$i][0] ?></div>
                    <div class="announcement__p"><?php echo $announcements[$i][1] ?></div>
                    <?php
                      if($announcements[$i][4] != ''){
                    ?>
                      <div class="announcement__file">
                        <div class="announcement__file-label">Archivos adjuntos:</div>
                        <a href="<?php echo $announcements[$i][4] ?>" class="file-element" download=""><i class="fa-regular fa-file-lines"></i> <span class="file-name"><?php echo $announcements[$i][4] ?></span></a>
                      </div>
                    <?php 
                      }
                    ?>
                  </div>
                  <?php
                    if($announcements[$i][3] != ''){
                  ?>
                    <img class="announcement__img" src="<?php echo $announcements[$i][3] ?>" alt="announcement-image">
                  <?php 
                    }
                  ?>
                </div>
                <?php
              }
              ?>
            </div>
            <?php
          } else {
            ?>
            <div class="neutral-message"><i class="fas fa-exclamation-triangle"></i> No hay anuncios publicados.</div>
            <?php
          }
        ?>
      </div>
      
    </main>
    <script src="../../Controllers/file-name.js"></script>
  </body>
  </html>
  <?php 
  } else {
    include('../../Models/logout.php');
    $location = header('Location: ../index.php');
  }
?>
