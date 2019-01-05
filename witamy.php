<?php
    session_start(); 

    if(isset($_SESSION['udanarejestracja']))
    {
        header('Location: index.php');
        $_SESSION['yesregistration']='<div class="col-sm-12 alert alert-success">Udało ci się zarejestrować. Może teraz się zalogować!</div>';
        exit();
    }
    else
    {
        unset($_SESSION['udanarejestracja']);
    }

//usuwanie zmiennych wpisanych do fr
//if(isset($_SESSION['fr_nick']) unset($_SESSION['fr_nick']);
//if(isset($_SESSION['fr_email']) unset($_SESSION['fr_email']);
//if(isset($_SESSION['fr_haslo1']) unset($_SESSION['fr_haslo1']);
//if(isset($_SESSION['fr_haslo2']) unset($_SESSION['fr_haslo2']);
//if(isset($_SESSION['fr_regulamin']) unset($_SESSION['fr_regulamin']);
   
//usuwanie bledow rejestracji
//if(isset($_SESSION['error_nick']) unset($_SESSION['error_nick']);
//if(isset($_SESSION['error_mail']) unset($_SESSION['error_mail']);
//if(isset($_SESSION['error_haslo']) unset($_SESSION['error_haslo']);
//if(isset($_SESSION['error_rules']) unset($_SESSION['error_rules']);
//if(isset($_SESSION['error_bot']) unset($_SESSION['error_bot']);
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="edge,chrome=1" />
    <title>Sklep internetowy</title>
</head>
<body>
    <h1>Rejestracja przebiegła pomyślnie, możesz zalogować się na swoje konto.</h1>
    <a href="index.php">Zaloguj się na swoje konto!</a>
 
</body>
</html>