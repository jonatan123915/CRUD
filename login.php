<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 
   $_SESSION['username'] = $_POST['username'];
  
  header("Location: ventas.php");
  
  exit();

}

?>


<!DOCTYPE html>
<html lang="es">

<head>
 
   <meta charset="UTF-8">
   
 <title>Iniciar Sesión</title>

</head>

<body>

    <form method="POST" action="login.php">
 
       Usuario: <input type="text" name="username" required>
   
     <button type="submit">Iniciar Sesión</button>
  
  </form>

</body>

</html>