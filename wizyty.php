<?php
    session_start(); 
    if(!isset($_SESSION['zalogowany']))
    {
        header('Location: index.php');
        exit();
    }
    if($_SESSION['status']!="PACJENT")
    {
        header('Location: index.php');
        exit();
    }
$host = 'localhost';
$dbname = 'allergology';
$user = 'root';
$pass = '';

if(isset($_POST['submit']))
{
    $DateTime = $_POST['DateTime'];
    $DateTimeCheck = date("Y-m-d H:00:00",strtotime($DateTime));
    $TypeApp = $_POST['TypeApp'];
    $ID_user = $_SESSION['ID'];
    
    require_once "connect.php";    //sprawdzanie ile pacjent moze miec umowionych badan
    $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
    if ($polaczenie->connect_error) 
    {
        die("Connection failed: " . $polaczenie->connect_error);
    } 
    $rezultat=$polaczenie->query("SELECT ID_user FROM appointment WHERE DateTime='$DateTime'");
    if(!$rezultat)throw new exception($polaczenie->error); // ! neguje
    $ile_takich_dat=$rezultat->num_rows;
    if($ile_takich_dat>=1)  
    {
        $result='<div class="alert alert-info">Przeraszamy ale badanie na tą godzinę zostało juz zarezerwowane!</div>';
    }
    else
    {
        $rezultat=$polaczenie->query("SELECT ID_user FROM appointment WHERE ID_user='$ID_user'");
        if(!$rezultat)throw new exception($polaczenie->error); // ! neguje
        $ile_takich_nn=$rezultat->num_rows;
        if($ile_takich_nn<2)  //dla pacjenta dwa badania po ID_user
        {
            if($TypeApp=="Konsultacje z lekarzem") //jedno konsultacje dla pacjenta
            {
                $rezultat=$polaczenie->query("SELECT ID_user, TypeApp FROM appointment WHERE TypeApp='$TypeApp' HAVING ID_user=$ID_user");
                if(!$rezultat)throw new exception($polaczenie->error); // ! neguje
                $ile_takich_nnn=$rezultat->num_rows;
                if($ile_takich_nnn<1)
                {
                    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sql = "INSERT INTO appointment (ID_user, DateTime, TypeApp ) VALUES ( :ID_user, :DateTime, :TypeApp )";

                    $q = $conn->prepare($sql);
                    $q->execute(array(':ID_user'=>$ID_user,':DateTime'=>$DateTime,':TypeApp'=>$TypeApp));
                    $result='<div class="alert alert-success">Twoje spotkanie zostało umówione!</div>'; 
                    $conn = null;
                }
                else
                {
                    $result='<div class="alert alert-info">Masz już umówione spotkanie na konsultacje z lekarzem!</div>';
                }
            }
            if($TypeApp=="Badanie alergiczne")  //jedno badanie dla pacjenta
            {
                $rezultat=$polaczenie->query("SELECT ID_user, TypeApp FROM appointment WHERE TypeApp='$TypeApp' HAVING ID_user=$ID_user");
                if(!$rezultat)throw new exception($polaczenie->error); // ! neguje
                $ile_takich_nnnn=$rezultat->num_rows;
                if($ile_takich_nnnn<1)
                {
                    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sql = "INSERT INTO appointment (ID_user, DateTime, TypeApp ) VALUES ( :ID_user, :DateTime, :TypeApp )";

                    $q = $conn->prepare($sql);
                    $q->execute(array(':ID_user'=>$ID_user,':DateTime'=>$DateTime,':TypeApp'=>$TypeApp));
                    $result='<div class="alert alert-success">Twoje spotkanie zostało umówione!</div>';                    
                    $conn = null;
                }
                else
                {
                $result='<div class="alert alert-info">Masz już umówione spotkanie na badanie alergiczne!</div>';
                }
            }
        }
        else //jesli ma badania w bazie
        {
            if($TypeApp=="Konsultacje z lekarzem")
            {
                $result='<div class="alert alert-info">Masz już umówione spotkanie na konsultacje z lekarzem!</div>';
            }
            if($TypeApp=="Badanie alergiczne")
            {
                $result='<div class="alert alert-info">Masz już umówione spotkanie na badanie alergiczne!</div>';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Poradnia Alergologiczna</title>
    <meta name="description" content="Poradnia alergologiczna">
    <meta name="keywords" content="Poradnia, Alergologiczna, Alergia, Astma, Badania">

    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans|Raleway|Candal">
    <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body id="myPage" data-spy="scroll" data-target=".navbar" data-offset="60">
    <!--navbar-->
    <nav class="navbar-default bg-color-2">
        <div class="container">
            <div class="col-md-12">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#"><img src="img/logo.png" class="img-responsive" style="width: 100px; margin-top: -5px;"></a>
                </div>
                <div class="collapse navbar-collapse navbar-right" id="myNavbar">
                    <ul class="nav navbar-nav">
                        <li class=""><a href="panel_pacjent.php">Panel pacjenta</a></li>
                        <li class=""><a href="wizyty.php">Umów się na badania</a></li>
                        <li class=""><a href="logout.php">Wyloguj się</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <!--navbar-->

    <!--about-->
    <section id="about" class="section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="section-title">
                        <h2 class="head-title lg-line">Umów się na badania</h2>
                        <hr class="botm-line">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div style="visibility: visible;" class="col-sm-12 more-features-box">
                                <div class="more-features-box-text">
                                    <div class="more-features-box-text-icon"> 
                                        <i class="fa fa-angle-right" aria-hidden="true"></i> 
                                    </div>
                                    <div class="more-features-box-text-description h4">
                                        <h3>Umów się na badanie specialistyczne lub na konsultacje z jednym z naszych lekarzy.</h3>
                                            <p>Poniżej możesz dokonać rejestracji na określone badanie. Najpierw wybierz rodzaj badania a potem termin w jakim zostanie ono wykonane.</p>
                                            <h4>Proszę uwzględnić dni oraz godziny pracy naszej poradni, nieprawidłowo wprowadzony czas wizyty zostanie pominięty!</h4><br/>
                                            <form action="<?php ?>" method="post" class="col-sm-4">
                                                <h4>Wybierz rodzaj badania:</h4>
                                                <select input type="text" id="Booking" class=" btn btn-default dropdown-toggle" data-toggle="dropdown" name="TypeApp" value="">
                                                <option value="Konsultacje z lekarzem">Konsultacje z lekarzem</option>
                                                <option value="Badanie alergiczne">Badanie alergiczne</option>
                                                </select>                       
                                                <h4><br/>Wybierz godzinę:</h4>
                                                <div class="input">
                                                <input type="datetime-local" id="DateTime" step="1200" class="control-label form-control" name="DateTime"><br/>
                                                <input type="submit" class="btn btn-form" name="submit" value="Umów badanie">
                                                    <?php if(isset($_SESSION['yesregistration']))
                                                    {
                                                        echo'<div class="alert">'.$_SESSION['yesregistration'].'</div>';
                                                        unset($_SESSION['yesregistration']);
                                                    } ?>    
                                                </div>
                                                <div class="info col-sm-10" role="alert"><br/>
                                                    <?php  
                                                    if(isset($result))
                                                        {
                                                            echo $result;
                                                            unset ($result);
                                                        }
                                                    ?>    
                                                </div>
                                               
                                            </form>
                                      </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--/ about-->


    <!--footer-->
    <footer id="footer">
        <div class="top-footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-sm-4 marb20">
                        <div class="ftr-tle">
                            <h4 class="white no-padding">Kontakt:</h4>
                        </div>
                        <div class="info-sec">
                                <div class="body">Adres: Ul. Wiejska 45A
                                    <br />15-351 Białystok</div>
                                <br />
                                <div class="body"> E-mail: poradnia@alergologiczna.pl
                                    <br />Rejestracja telefoniczna: 123-456-789</div>
                                <br />
                                <div class="body">Godziny przyjęć:
                                    <br />Poniedziałek-Piątek: 8.00-15.00
                                    <br />Sobota-Niedziela: nieczynne</div><br />
                                <ul class="social-icon">
                                    <li class="bglight-blue"><i class="fa fa-facebook"></i></li>
                                    <li class="bgred"><i class="fa fa-google-plus"></i></li>
                                    <li class="bgdark-blue"><i class="fa fa-linkedin"></i></li>
                                    <li class="bglight-blue"><i class="fa fa-twitter"></i></li>
                                </ul>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 marb20">
                        <div class="ftr-tle">
                            <h4 class="white no-padding">Menu</h4>
                        </div>
                        <div class="info-sec">
                            <ul class="quick-info">
                                <li><a href="panel_pacjent.php"><i class="fa fa-circle"></i>Panel pacjenta</a></li>
                                <li><a href="wizyty.php"><i class="fa fa-circle"></i>Umów się na wizytę</a></li>
                                <li><a href="logout.php"><i class="fa fa-circle"></i>Wyloguj się</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 marb20">
                        <div class="ftr-tle">
                            <h4 class="white no-padding">Znajdź nas</h4>
                        </div>
                        <div class="info-sec">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1036.324091499109!2d23.145700273370895!3d53.11666789750994!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x471ffbfb9415aee5%3A0x1f6449ccc6c966f7!2sPolitechnika+Bia%C5%82ostocka!5e0!3m2!1spl!2spl!4v1543250882091" width="350" height="250" frameborder="0" style="border:0" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-line">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        © Copyright Poradnia alergologiczna. Implemented by Kamil Janikowski.
                        <div class="credits">
                            <!--
                All the links in the footer should remain intact.
                You can delete the links only if you purchased the pro version.
                Licensing information: https://bootstrapmade.com/license/
                Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/buy/?theme=Medilab
              -->
                            Designed by <a href="https://bootstrapmade.com/">BootstrapMade.com</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!--/ footer-->

    <script src='https://www.google.com/recaptcha/api.js'></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery.easing.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/custom.js"></script>


</body>

</html>
