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
    if(isset($_POST['submit'])) //  @@@@@@@@@@@@@@ DODAWANIE BADAN
    {
        $wszystko_ok=true;  
        $leszczyna=$_POST['leszczyna'];
        $olsza=$_POST['olsza'];
        $brzoza=$_POST['brzoza'];
        $topola=$_POST['topola'];
        $dab=$_POST['dab'];
        $trawy=$_POST['trawy'];
        $babka_lancetowata=$_POST['babka_lancetowata'];
        $szczaw=$_POST['szczaw'];
        $pokrzywa=$_POST['pokrzywa'];
        $komosa=$_POST['komosa'];
        $bylica=$_POST['bylica'];
        $ambrozja=$_POST['ambrozja'];
        $cladosporium=$_POST['cladosporium'];
        $alternaria=$_POST['alternaria'];
        $analiza=$_POST['analiza'];
        $date=$_POST['date'];

        $leszczyna = str_replace(",",".",$leszczyna); //zamiana przecinki na kropki
        $olsza = str_replace(",",".",$olsza); 
        $brzoza = str_replace(",",".",$brzoza); 
        $topola = str_replace(",",".",$topola); 
        $dab = str_replace(",",".",$dab); 
        $trawy = str_replace(",",".",$trawy); 
        $babka_lancetowata = str_replace(",",".",$babka_lancetowata); 
        $szczaw = str_replace(",",".",$szczaw); 
        $pokrzywa = str_replace(",",".",$pokrzywa); 
        $komosa = str_replace(",",".",$komosa); 
        $bylica = str_replace(",",".",$bylica); 
        $ambrozja = str_replace(",",".",$ambrozja); 
        $cladosporium = str_replace(",",".",$cladosporium); 
        $alternaria = str_replace(",",".",$alternaria); 

        if((strlen($leszczyna)==0) || (strlen($olsza)==0) || (strlen($brzoza)==0) || (strlen($topola)==0) || (strlen($dab)==0) || (strlen($trawy)==0) || (strlen($babka_lancetowata)==0) || (strlen($szczaw)==0) || (strlen($pokrzywa)==0) || (strlen($komosa)==0) || (strlen($bylica)==0) || (strlen($ambrozja)==0) || (strlen($cladosporium)==0) || (strlen($alternaria)==0) || (strlen($date)==0)) //sprawdza czy zostaly wypelnione pola
        {
           $wszystko_ok=false;
           $_SESSION['error_nazwisko']='<div class="col-sm-12 alert alert-info">Wypełnij wszystkie pola alergenów!</div>';
        }
        else
        {
        require_once"connect.php";
                mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); //raportujemy tylko wyjatki czyli exceptio
                $polaczenie=new mysqli($host, $db_user, $db_password, $db_name);  
                if($polaczenie->connect_errno!=0)//wypelni sie jezeli bedzie blad polaczenia
                {
                    throw new exception(mysqli_connect_errno); // oznacza rzuc nowym wyjatkiem
                }
                else //wypelni sie jesli wykona sie polaczenie do bazy
                {
                    if($polaczenie->query("INSERT INTO ige (ID_ige, ID_user) SELECT NULL, ID FROM users WHERE imie='".$_SESSION['imieview']."' AND nazwisko='".$_SESSION['nazwiskoview']."'"))
                    {
                        if($polaczenie->query("UPDATE ige SET leszczyna='$leszczyna', olsza='$olsza', brzoza='$brzoza', topola='$topola', dab='$dab', trawy='$trawy', babka_lancetowata='$babka_lancetowata', szczaw='$szczaw', pokrzywa='$pokrzywa', komosa='$komosa', bylica='$bylica', ambrozja='$ambrozja', cladosporium='$cladosporium', alternaria='$alternaria', analiza='$analiza', data='$date' ORDER BY ID_ige DESC LIMIT 1"))
                        {
                            $_SESSION['gut']='<div class="col-sm-12 alert alert-success">Dane zostały dodane prawidłowo!</div>';        
                        }
                    }
                    else
                    { 
                        $_SESSION['error_imie']='<div class="col-sm-12 alert alert-info">Wpisz prawidłowe dane pacjenta!</div>';
                    }
                    $polaczenie->close();  
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
   
    <!--@@@@@@@@  NAVBAR,  NAGLOWEK  @@@@@@@@@@@@@-->
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
                        <li class=""><a href="logout.php">Wyloguj się</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <!--/-->

    <!--@@@@@@@@  ZAWARTOSC, ADD IGE  @@@@@@@@@@@@@-->
    <section id="about" class="section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 h4">
                    <div class="section-title">
                        <h2 class="head-title lg-line col-sm-10">DANE PACJENTÓW</h2>
                        <a href="medical_card.php" class="btn btn-primary btn-lg active col-sm-2" role="button" aria-pressed="true">WSTECZ</a>
                        <hr class="botm-line">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div style="visibility: visible;" class="col-sm-12 more-features-box">
                                <div class="more-features-box-text">
                                    <div class="more-features-box-text-icon"> <i class="fa fa-angle-right" aria-hidden="true"></i> </div>
                                    <div class="more-features-box-text-description">
                                        <h3>Dodaj wyniki badań dla pacjentów</h3>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et. Ut wisi enim ad minim veniam, quis nostrud.</p>
                                        <form action="" method="post">
                                            <label class="control-label col-md-12"></label>
                                            <label class="control-label col-md-2">Leszczyna:</label>
                                            <div class="col-md-2"><input type="text" name="leszczyna" class="form-control" id="leszczyna" data-rule="minlen:4" /></div>

                                            <label class="control-label col-md-2">Olsza:</label>
                                            <div class="col-md-2"><input type="text" name="olsza" class="form-control" id="olsza" data-rule="minlen:4" /></div>
                                            <label class="control-label col-md-3"></label>
                                            <label class="control-label col-md-2">Brzoza:</label>
                                            <div class="col-md-2"><input type="text" name="brzoza" class="form-control" id="brzoza" data-rule="minlen:4" /></div>

                                            <label class="control-label col-md-2">Topola:</label>
                                            <div class="col-md-2"><input type="text" name="topola" class="form-control" id="topola" data-rule="minlen:4" /></div>

                                            <label class="control-label col-md-2">Dąb:</label>
                                            <div class="col-md-2"><input type="text" name="dab" class="form-control" id="dab" data-rule="minlen:4" /></div>

                                            <label class="control-label col-md-2">Trawy:</label>
                                            <div class="col-md-2"><input type="text" name="trawy" class="form-control" id="trawy" data-rule="minlen:4" /></div>

                                            <label class="control-label col-md-12"></label>
                                            <label class="control-label col-md-2">Babka Lancetowata:</label>
                                            <div class="col-md-2"><input type="text" name="babka_lancetowata" class="form-control" id="babka_lancetowata" data-rule="minlen:4" /></div>

                                            <label class="control-label col-md-2">Szczaw:</label>
                                            <div class="col-md-2"><input type="text" name="szczaw" class="form-control" id="szczaw" data-rule="minlen:4" /></div>

                                            <label class="control-label col-md-2">Pokrzywa:</label>
                                            <div class="col-md-2"><input type="text" name="pokrzywa" class="form-control" id="pokrzywa" data-rule="minlen:4" /></div>
                                            <label class="control-label col-md-12"></label>
                                            <label class="control-label col-md-2">Komosa:</label>
                                            <div class="col-md-2"><input type="text" name="komosa" class="form-control" id="komosa" data-rule="minlen:4" /></div>

                                            <label class="control-label col-md-2">Bylica:</label>
                                            <div class="col-md-2"><input type="text" name="bylica" class="form-control" id="bylica" data-rule="minlen:4" /></div>

                                            <label class="control-label col-md-2">Ambrozja:</label>
                                            <div class="col-md-2"><input type="text" name="ambrozja" class="form-control" id="ambrozja" data-rule="minlen:4" /></div>
                                            <label class="control-label col-md-12"></label>
                                            <label class="control-label col-md-2">Cladosporium:</label>
                                            <div class="col-md-2"><input type="text" name="cladosporium" class="form-control" id="cladosporium" data-rule="minlen:4" /></div>

                                            <label class="control-label col-md-2">Alternaria:</label>
                                            <div class="col-md-2"><input type="text" name="alternaria" class="form-control" id="alternaria" data-rule="minlen:4" /></div>

                                            <label class="control-label col-md-5">Analiza wyników:</label>
                                            <div class="col-md-12"><input type="text" name="analiza" class="form-control" id="analiza" data-rule="minlen:4" /></div>
                                            
                                            <label class="control-label col-md-12"></label>       
                                            <label class="control-label col-md-2">Data badania:</label>
                                            <div class="col-md-3"><input type="date" name="date" class="form-control" id="date" data-rule="minlen:4" /></div>
                                            <label class="control-label col-md-12"></label>
                                            <div class="form-action">
                                                <input type="submit" class="btn btn-form control-label col-md-3" name="submit" value="Dodaj wyniki badań">
                                                <?php 
                                                if(isset($_SESSION['gut']))
                                                {
                                                    echo'<div class="alert">'.$_SESSION['gut'].'</div>';
                                                    unset($_SESSION['gut']);
                                                }  
                                                ?>
                                                <?php
                                                if(isset($_SESSION['error_imie']))
                                                {
                                                    echo'<div class="error">'.$_SESSION['error_imie'].'</div>';
                                                    unset($_SESSION['error_imie']); 
                                                }
                                                ?>
                                                <?php
                                                if(isset($_SESSION['error_nazwisko']))
                                                {
                                                    echo'<div class="error">'.$_SESSION['error_nazwisko'].'</div>';  
                                                    unset($_SESSION['error_nazwisko']); 
                                                }
                                                ?>
                                                <?php
                                                if(isset($_SESSION['error_view2']))
                                                {
                                                    echo'<div class="error">'.$_SESSION['error_view2'].'</div>';  
                                                    unset($_SESSION['error_view2']); 
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
    <!--/-->

    <!--@@@@@@@@  STOPKA  @@@@@@@@@@@@@-->
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
    <!--/-->

    <script src='https://www.google.com/recaptcha/api.js'></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery.easing.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/custom.js"></script>


</body>

</html>
