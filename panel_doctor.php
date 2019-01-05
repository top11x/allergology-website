<?php
    session_start(); 
    if(!isset($_SESSION['zalogowany']))
    {
        header('Location: index.php');
        exit();
    }
    if($_SESSION['status']!="LEKARZ")
    {
        header('Location: index.php');
        exit();
    }
   if(isset($_POST['submit_view']))   // do wyswietlania wynikow badań
    {
        $imieview=$_POST['imieview'];
        $nazwiskoview=$_POST['nazwiskoview'];
        $_SESSION['imieview']=$_POST['imieview'];
        $_SESSION['nazwiskoview']=$_POST['nazwiskoview'];
        require_once"connect.php";
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); //raportujemy tylko wyjatki czyli exceptio
        $polaczenie=new mysqli($host, $db_user, $db_password, $db_name);  
        if($polaczenie->connect_errno!=0)
        {
            throw new exception(mysqli_connect_errno);
        }
        else
        {
            $sql = "SELECT imie, nazwisko FROM users WHERE imie='".$_SESSION['imieview']."' AND nazwisko='".$_SESSION['nazwiskoview']."' ";
            $result = $polaczenie->query($sql);
            if ($result->num_rows >= 1) //sprawdza czy istnieje uzytkownik w bazie
            {
                header('Location: medical_card.php');
                exit();
            }
            else
            {
                $_SESSION['error_view']='<div class="col-sm-12 alert alert-info">Brak pracjenta w bazie lub wprowadzono złe imię lub nazwisko!</div>';
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
                                <li class=""><a href="panel_doctor.php">Panel lekarza</a></li>
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
                        <h2 class="head-title lg-line">PANEL DOKTORA</h2>
                        <hr class="botm-line">
                        <div class="col-md-12 col-sm-12 col-xs-12 h4">
                            <div style="visibility: visible;" class="col-sm-12 more-features-box">
                                <div class="more-features-box-text">
                                    <div class="more-features-box-text-icon"> <i class="fa fa-angle-right" aria-hidden="true"></i> </div>
                                    <div class="more-features-box-text-description">
                                    <?php
                                        echo "<h3><b>Witaj doktorze </b>".$_SESSION['imie']." ".$_SESSION['nazwisko']."</h3>";   
                                        ?>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et. Ut wisi enim ad minim veniam, quis nostrud.</p>
                                    </div>
                                </div>
                                <label class="control-label col-md-12"></label>                                               
                                <label class="control-label col-md-12"></label>
                                <label class="control-label col-md-12"></label>
                                <label class="control-label col-md-12"></label>
                                <label class="control-label col-md-12"></label>
                                <div class="more-features-box-text">
                                    <div class="more-features-box-text-icon"> <i class="fa fa-angle-right" aria-hidden="true"></i> </div>
                                    <div class="more-features-box-text-description">
                                        <h3>Lista umówionych spotkań z pacjentami.</h3>
                                        <p>Poniżej możesz podejrzeń twoje nadchodzące umówione badania z pacjentami.</p><br/>
                                        <?php
                                            require_once "connect.php";
                                            $conn = new mysqli($host, $db_user, $db_password, $db_name);
                                            if ($conn->connect_error) 
                                            {
                                                die("Connection failed: " . $conn->connect_error);
                                            } 
                                            $sql = "SELECT DateTime, TypeApp, imie, nazwisko, adres, miasto, tel FROM appointment, users WHERE  ID_user=ID AND DATE_SUB(CURRENT_DATE(),INTERVAL 1 DAY) <= DateTime"; //current_date pokazuje rekordy poprzednie z okreslonym interwałem ale i rowniez pokazuje wszystko co w przyszłości
                                            $result = $conn->query($sql);
                                            if ($result->num_rows > 0) 
                                            {
                                                echo "<table><tr><th><h4>Data i czas spotkania &nbsp;&nbsp;&nbsp;</h4></th>
                                                <th><h4>Rodzaj badania &nbsp;&nbsp;&nbsp;</h4></th>
                                                <th><h4>Imię &nbsp;&nbsp;&nbsp;</h4></th>
                                                <th><h4>Nazwisko &nbsp;&nbsp;&nbsp;</h4></th>
                                                <th><h4>Adres &nbsp;&nbsp;&nbsp;</h4></th>
                                                <th><h4>Miasto &nbsp;&nbsp;&nbsp;</h4></th>
                                                <th><h4>Numer telefonu &nbsp;&nbsp;&nbsp;</h4></th></tr>";
                                                while($row = $result->fetch_assoc()) 
                                                {
                                                echo "<tr><th>".$row["DateTime"]."</th><td>".$row["TypeApp"]."</td><td>".$row["imie"]."</td><td>".$row["nazwisko"]."</td><td>".$row["adres"]."</td><td>".$row["miasto"]."</td><td>".$row["tel"]."</td></tr>";
                                                }
                                                echo "</table>";
                                            } 
                                            else 
                                            {
                                                echo "0 results";
                                            }
                                            $conn->close();
                                        ?>
                                    </div>
                                </div>
                                <label class="control-label col-md-12"></label>                                               
                                <label class="control-label col-md-12"></label>
                                <label class="control-label col-md-12"></label>
                                <label class="control-label col-md-12"></label>
                                <label class="control-label col-md-12"></label> 
                                <div class="more-features-box-text">
                                    <div class="more-features-box-text-icon"> <i class="fa fa-angle-right" aria-hidden="true"></i> </div>
                                    <div class="more-features-box-text-description">
                                        <h3>Zaloguj się do karty pacjenta</h3>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et. Ut wisi enim ad minim veniam, quis nostrud.</p>
                                        <form action="<?php ?>" method="post">
                                            <label class="control-label col-md-12"></label>
                                            <label class="control-label col-sm-2">Imię pacjenta:</label>
                                            <div class="col-sm-4"><input type="text" name="imieview" method="post" class="form-control" id="imieview" placeholder="Imię" data-rule="minlen:4" /></div>
                                            <label class="control-label col-md-12"></label>
                                            <label class="control-label col-sm-2">Nazwisko pacjenta:</label>
                                            <div class="col-sm-4"><input type="text" name="nazwiskoview" method="post" class="form-control" id="nazwiskoview" placeholder="Nazwisko" data-rule="minlen:4" /></div>
                                            <label class="control-label col-md-12"></label>
                                            <div class="form-action" method="post">
                                                <input type="submit" href="medical_card.php" class="col-sm-3 btn btn-form" name="submit_view" value="Przejdż do karty pacjenta">
                                                <label class="control-label col-md-12"></label>
                                                        <?php
                                                if(isset($_SESSION['error_view']))
                                                {
                                                    echo'<div class="error">'.$_SESSION['error_view'].'</div>';  
                                                    unset($_SESSION['error_view']); 
                                                }
                                                ?>
                                                <label class="control-label col-md-12"></label>                                               <label class="control-label col-md-12"></label>
                                                <label class="control-label col-md-12"></label>
                                                <label class="control-label col-md-12"></label>
                                                <label class="control-label col-md-12"></label>      
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
                                <br/>15-351 Białystok</div>
                                <br/><div class="body"> E-mail: poradnia@alergologiczna.pl
                                <br/>Rejestracja telefoniczna: 123-456-789</div>
                                <br/><div class="body">Godziny przyjęć: 
                                <br/>Poniedziałek-Piątek: 8.00-15.00
                                <br/>Sobota-Niedziela: nieczynne</div><br/>
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
                                <li><a href="panel_doctor.php"><i class="fa fa-circle"></i>Panel lekarza</a></li>
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
                        © Copyright Medilab Theme. All Rights Reserved
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
