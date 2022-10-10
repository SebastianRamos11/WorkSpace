<?php
  include_once "../../Models/connection.php";
  session_start();
  if (isset($_SESSION['id'])) {
  $read_query = "SELECT * FROM persona WHERE ID_Persona =".$_SESSION['id'];
  $query_result = mysqli_query($dbConnection, $read_query) or die(mysqli_error($dbConnection));
  $result_array = mysqli_fetch_all($query_result, MYSQLI_NUM);
  
  $turned_evidence = $_GET['evidence'];  

  $publication = "SELECT P.ID_Persona, P.asunto, P.descripcion, P.fecha, P.fecha_limite, P.tipo_publicacion, P.url, A.ID_Programa FROM publicacion P JOIN ambiente_virtual A ON P.ID_Persona = A.ID_Persona WHERE ID_Publicacion = $turned_evidence;";
  $publication_result = mysqli_query($dbConnection, $publication) or die(mysqli_error($dbConnection));
  $publication_array = mysqli_fetch_all($publication_result, MYSQLI_NUM);

  $instructor_id= $publication_array[0][0];

  $instructor = "SELECT nombres, apellidos FROM persona WHERE ID_Persona = $instructor_id";
  $instructor_result = mysqli_query($dbConnection, $instructor) or die(mysqli_error($dbConnection));
  $instructor_array = mysqli_fetch_all($instructor_result, MYSQLI_NUM);

  $evidence_id = "SELECT ID_Evidencia FROM evidencia WHERE ID_Publicacion = $turned_evidence AND ID_Persona =".$_SESSION['id'];
  $evidence_id_result = mysqli_query($dbConnection, $evidence_id) or die(mysqli_error($dbConnection));
  $evidence_id_array = mysqli_fetch_all($evidence_id_result, MYSQLI_NUM);
  $evidence_id = $evidence_id_array[0][0];

  $evidence = "SELECT url, descripcion, observacion, nota FROM evidencia WHERE ID_Evidencia = $evidence_id AND ID_Persona =".$_SESSION['id'];
  $evidence_result = mysqli_query($dbConnection, $evidence) or die(mysqli_error($dbConnection));
  $evidence_array = mysqli_fetch_all($evidence_result, MYSQLI_NUM);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../img/favicon.ico" />
    <script src="https://kit.fontawesome.com/643b0ccc65.js" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../css/aprendiz.css" />
    <title>Tu evidencia</title>
</head>
<body>
    <!-- ALERTS -->
    <!-- Upload successfully -->
    <?php 
      if(isset($_GET['message']) and $_GET['message'] == 'updated'){
    ?>
      <script>
          Swal.fire({
              icon: 'success',
              title: '¡Evidencia entregada!',
              text: '¡Tu evidencia ha sido entregada correctamente!'
          });
      </script>
    <?php 
      }
    ?>
    <?php include './sidebar.php' ?>
        <h1 class="main-content__header">Evidencia entregada</h1>
       <!-- PUBLICATION -->
       <div class="evidence">
            <!-- HEADER -->
            <div class="evidence__header">
                <div class="evidence__instructor">
                    <img src="../img/default.jpeg" class="evidence__instructor-photo"></img>
                    <div class="evidence__instructor-name"><?php echo $instructor_array[0][0] ; echo " " ; echo $instructor_array[0][1] ?></div>
                </div>
                <div class="evidence__info">
                    <div class="evidence__info-date-limit"><?php echo $publication_array[0][4] ;?></div>
                    <div class="evidence__info-type"><?php echo $publication_array[0][5] ;?></div>
                </div>
            </div>
            <hr>

            <!-- CONTENT -->
            <div class="evidence__content">
                <div class="evidence__title"><?php echo $publication_array[0][1] ;?></div>
                <div class="evidence__p"><?php echo $publication_array[0][2] ;?></div>
                <?php
                  if($publication_array[0][6] != ''){
                ?>
                  <div class="evidence__file">
                    <i class="fa-regular fa-file-lines"></i>
                    <a class="file-name" href="<?php echo $publication_array[0][6];?>" download=""><?php echo $publication_array[0][6] ;?></a>
                  </div>
                <?php 
                  }
                ?>
            </div>  
        </div>

        <!-- RECORDED EVIDENCE -->
        <div class="turned-evidence">
          <div class="turned-evidence__data">
            <div class="turned-evidence__file">
              <i class="fa-regular fa-file-lines"></i>
              <a class="file-name" href="<?php echo $evidence_array[0][0]; ?>" download=""><?php echo $evidence_array[0][0]; ?></a>
            </div>
            <div class="turned-evidence__p"><?php echo $evidence_array[0][1]; ?></div>
          </div>
          <div class="turned-evidence__observation">
            <div class="turned-evidence__observation-label">Observación</div>
            <div class="turned-evidence__observation-p">
              <?php
                if($evidence_array[0][2]){
                  echo $evidence_array[0][2]; 
                } else{
                  echo "Sin observación...";
                }
              ?>
            </div>
          </div>
          <div class="turned-evidence__observation-calification">
            <?php
              if($evidence_array[0][3]){
                echo $evidence_array[0][3]; 
              } else{
                echo "--";
              }
            ?>/100
        </div>
        </div>
</body>
<script>
  const fileName = document.querySelectorAll('.file-name');

  fileName.forEach((e, i) => {
    fileName[i].textContent = fileName[i].textContent.replace('../file-store/', '');
    fileName[i].textContent = fileName[i].textContent.replace('evidences/', '');
  });
</script>
</html>

<?php 
  } else {
    include('../../Models/logout.php');
    $location = header('Location: ../index.php');
  }
?>