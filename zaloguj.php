<?php
    session_start(); 
    if((!isset($_POST['login'])) || (!isset($_POST['haslo'])))
    {
        header('Location: index.php');
        exit();
    }
    
    require_once "connect.php";
    $polaczenie=@new mysqli($host, $db_user, $db_password, $db_name);

    if($polaczenie->connect_errno!=0)
    {
        echo "Error".$polaczenie->connect_errno;
    }
    else
    {
        $login=$_POST['login'];
        $haslo=$_POST['haslo'];
        
        $login=htmlentities($login,ENT_QUOTES,"UTF-8");

        if($rezultat=@$polaczenie->query( sprintf("SELECT * FROM users WHERE user='%s'", mysqli_real_escape_string($polaczenie,$login))))
        {
          $ile_userow=$rezultat->num_rows;
          if($ile_userow>0)
          {  
            $wiersz=$rezultat->fetch_assoc();
                if(password_verify($haslo,$wiersz['pass']))
                {
                $_SESSION['zalogowany']=true;
                $_SESSION['ID']=$wiersz['ID'];  
                $_SESSION['user']=$wiersz['user'];
                $_SESSION['imie']=$wiersz['imie'];
                $_SESSION['nazwisko']=$wiersz['nazwisko'];
                $_SESSION['miasto']=$wiersz['miasto'];
                $_SESSION['adres']=$wiersz['adres'];
                $_SESSION['telefon']=$wiersz['tel'];
                $_SESSION['email']=$wiersz['email'];
                $_SESSION['status']=$wiersz['status'];
                //$_SESSION['karta']=$wiersz['karta'];

                unset($_SESSION['blad']);
                    if($_SESSION['status']=="PACJENT")
                    {
                        $rezultat->close();
                        header('Location: panel_pacjent.php');
                    }
                    else
                    {
                        $rezultat->close();
                        header('Location: panel_doctor.php');
                    }

                }
                 else
                {
                    $_SESSION['blad']='<div class="col-sm-12 alert alert-info">Nieprawidlowy login lub hasło!</div>';
                    header('Location: index.php');
                } 
          }
            else
            {
                $_SESSION['blad']='<div class="col-sm-12 alert alert-info">Nieprawidlowy login lub hasło!</div>';
                header('Location: index.php');
            }
        }
        $polaczenie->close();
    }

?>
