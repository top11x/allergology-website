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
                        <li class=""><a href="panel_doctor.php">Panel lekarza</a></li>                                
                        <li class=""><a href="stat.php">Statystyki pacjentów</a></li>
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
                        <a href="panel_doctor.php" class="btn btn-primary btn-lg active col-sm-2" role="button" aria-pressed="true">WSTECZ</a>
                        <hr class="botm-line">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div style="visibility: visible;" class="col-sm-12 more-features-box">
                                <div class="more-features-box-text">
                                    <div class="more-features-box-text-icon"> <i class="fa fa-angle-right" aria-hidden="true"></i> </div>
                                    <div class="more-features-box-text-description">
                                        <h3>Statystyki wyników badań wszystkich pacjentów naszej poradni</h3>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laboriosam illum itaque est tempore sit deleniti, magni harum vel maxime, possimus natus tempora aliquam doloremque tenetur quos eaque. Dolorem, dolorum, saepe!</p>
                                        <?php
                                        require_once"connect.php";
                                        $conn = new mysqli($host, $db_user, $db_password, $db_name);
                                        if ($conn->connect_error) 
                                        {
                                            printf("Connection failed: " . $conn->connect_error);
                                            exit();
                                        }
                                        $sql = "SELECT ROUND(AVG(leszczyna),2) AS stat, ROUND(AVG(olsza),2) AS stat2, ROUND(AVG(brzoza),2) AS stat3, ROUND(AVG(topola),2) AS stat4, ROUND(AVG(dab),2) AS stat5, ROUND(AVG(trawy),2) AS stat6, ROUND(AVG(babka_lancetowata),2) AS stat7, ROUND(AVG(szczaw),2) AS stat8, ROUND(AVG(pokrzywa),2) AS stat9, ROUND(AVG(komosa),2) AS stat10, ROUND(AVG(bylica),2) AS stat11, ROUND(AVG(ambrozja),2) AS stat12, ROUND(AVG(cladosporium),2) AS stat13, ROUND(AVG(alternaria),2) AS stat14 FROM ige ";
                                        $result = $conn->query($sql);
                                        if ($result->num_rows > 0) 
                                        {
                                            while($row = $result->fetch_assoc()) 
                                        {?>
                                        <label class="col-md-3 col-sm-3"><h4>Leszczyna - <?php echo "".$row["stat"]." kU/I"; ?></h4></label>
                                        <label class="col-md-7 col-sm-7">
                                        <div class="panel-body">
                                            <div class="progress">
                                                <div class="progress-bar 
                                                <?php if($row["stat"]<=20){ ?> php progress-bar-info <?php }
                                                                elseif($row["stat"]<=40){ ?> php progress-bar-success <?php }
                                                                elseif($row["stat"]<=60){ ?> php progress-bar-warning <?php }
                                                                elseif($row["stat"]<=80){ ?> php progress-bar-danger <?php }
                                                                elseif($row["stat"]<=100){ ?> <?php } ?> 
                                                progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="<?php echo "".$row["stat"].""; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo "".$row["stat"]."%"; ?>;"></div>
                                            </div>
                                        </div></label>
                                                          
                                        <label class="col-md-3 col-sm-3"><h4>Olsza - <?php echo "".$row["stat2"]." kU/I"; ?></h4></label>
                                        <label class="col-md-7 col-sm-7">
                                        <div class="panel-body">
                                            <div class="progress">
                                                <div class="progress-bar 
                                                <?php if($row["stat2"]<=20){ ?> php progress-bar-info <?php }
                                                                elseif($row["stat2"]<=40){ ?> php progress-bar-success <?php }
                                                                elseif($row["stat2"]<=60){ ?> php progress-bar-warning <?php }
                                                                elseif($row["stat2"]<=80){ ?> php progress-bar-danger <?php }
                                                                elseif($row["stat2"]<=100){ ?> <?php } ?> 
                                                progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="<?php echo "".$row["stat2"].""; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo "".$row["stat2"]."%"; ?>;"></div>
                                            </div>
                                        </div></label>
                                                        
                                                        <label class="col-md-3 col-sm-3"><h4>Brzoza - <?php echo "".$row["stat3"]." kU/I"; ?></h4></label>
                                                       <label class="col-md-7 col-sm-7">
                                                        <div class="panel-body">
                                                            <div class="progress">
                                                                <div class="progress-bar 
                                                                <?php if($row["stat3"]<=20){ ?> php progress-bar-info <?php }
                                                                elseif($row["stat3"]<=40){ ?> php progress-bar-success <?php }
                                                                elseif($row["stat3"]<=60){ ?> php progress-bar-warning <?php }
                                                                elseif($row["stat3"]<=80){ ?> php progress-bar-danger <?php }
                                                                elseif($row["stat3"]<=100){ ?> <?php } ?> 
                                                                progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="<?php echo "".$row["stat3"].""; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo "".$row["stat3"]."%"; ?>;"></div>
                                                            </div>
                                                        </div></label>
                                                                                                                
                                                        <label class="col-md-3 col-sm-3"><h4>Topola - <?php echo "".$row["stat4"]." kU/I"; ?></h4></label>
                                                       <label class="col-md-7 col-sm-7">
                                                        <div class="panel-body">
                                                            <div class="progress">
                                                                <div class="progress-bar 
                                                                <?php if($row["stat4"]<=20){ ?> php progress-bar-info <?php }
                                                                elseif($row["stat4"]<=40){ ?> php progress-bar-success <?php }
                                                                elseif($row["stat4"]<=60){ ?> php progress-bar-warning <?php }
                                                                elseif($row["stat4"]<=80){ ?> php progress-bar-danger <?php }
                                                                elseif($row["stat4"]<=100){ ?> <?php } ?> 
                                                                progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="<?php echo "".$row["stat4"].""; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo "".$row["stat4"]."%"; ?>;"></div>
                                                            </div>
                                                        </div></label>   
                                                                                                             
                                                        <label class="col-md-3 col-sm-3"><h4>Dąb - <?php echo "".$row["stat5"]." kU/I"; ?></h4></label>
                                                       <label class="col-md-7 col-sm-7">
                                                        <div class="panel-body">
                                                            <div class="progress">
                                                                <div class="progress-bar 
                                                                <?php if($row["stat5"]<=20){ ?> php progress-bar-info <?php }
                                                                elseif($row["stat5"]<=40){ ?> php progress-bar-success <?php }
                                                                elseif($row["stat5"]<=60){ ?> php progress-bar-warning <?php }
                                                                elseif($row["stat5"]<=80){ ?> php progress-bar-danger <?php }
                                                                elseif($row["stat5"]<=100){ ?> <?php } ?> 
                                                                progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="<?php echo "".$row["stat5"].""; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo "".$row["stat5"]."%"; ?>;"></div>
                                                            </div>
                                                        </div></label>
                                                                                                                
                                                        <label class="col-md-3 col-sm-3"><h4>Trawy - <?php echo "".$row["stat6"]." kU/I"; ?></h4></label>
                                                       <label class="col-md-7 col-sm-7">
                                                        <div class="panel-body">
                                                            <div class="progress">
                                                                <div class="progress-bar 
                                                                <?php if($row["stat6"]<=20){ ?> php progress-bar-info <?php }
                                                                elseif($row["stat6"]<=40){ ?> php progress-bar-success <?php }
                                                                elseif($row["stat6"]<=60){ ?> php progress-bar-warning <?php }
                                                                elseif($row["stat6"]<=80){ ?> php progress-bar-danger <?php }
                                                                elseif($row["stat6"]<=100){ ?> <?php } ?> 
                                                                progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="<?php echo "".$row["stat6"].""; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo "".$row["stat6"]."%"; ?>;"></div>
                                                            </div>
                                                        </div></label>
                                                                                                                
                                                        <label class="col-md-3 col-sm-3"><h4>Babka_Lancetowata - <?php echo "".$row["stat7"]." kU/I"; ?></h4></label>
                                                       <label class="col-md-7 col-sm-7">
                                                        <div class="panel-body">
                                                            <div class="progress">
                                                                <div class="progress-bar 
                                                                <?php if($row["stat7"]<=20){ ?> php progress-bar-info <?php }
                                                                elseif($row["stat7"]<=40){ ?> php progress-bar-success <?php }
                                                                elseif($row["stat7"]<=60){ ?> php progress-bar-warning <?php }
                                                                elseif($row["stat7"]<=80){ ?> php progress-bar-danger <?php }
                                                                elseif($row["stat7"]<=100){ ?> <?php } ?> 
                                                                progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="<?php echo "".$row["stat7"].""; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo "".$row["stat7"]."%"; ?>;"></div>
                                                            </div>
                                                        </div></label>
                                                                                                                
                                                        <label class="col-md-3 col-sm-3"><h4>Szczaw - <?php echo "".$row["stat8"]." kU/I"; ?></h4></label>
                                                       <label class="col-md-7 col-sm-7">
                                                        <div class="panel-body">
                                                            <div class="progress">
                                                                <div class="progress-bar 
                                                                <?php if($row["stat8"]<=20){ ?> php progress-bar-info <?php }
                                                                elseif($row["stat8"]<=40){ ?> php progress-bar-success <?php }
                                                                elseif($row["stat8"]<=60){ ?> php progress-bar-warning <?php }
                                                                elseif($row["stat8"]<=80){ ?> php progress-bar-danger <?php }
                                                                elseif($row["stat8"]<=100){ ?> <?php } ?> 
                                                                progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="<?php echo "".$row["stat8"].""; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo "".$row["stat8"]."%"; ?>;"></div>
                                                            </div>
                                                        </div></label>
                                                                                                                
                                                        <label class="col-md-3 col-sm-3"><h4>Pokrzywa - <?php echo "".$row["stat9"]." kU/I"; ?></h4></label>
                                                       <label class="col-md-7 col-sm-7">
                                                        <div class="panel-body">
                                                            <div class="progress">
                                                                <div class="progress-bar 
                                                                <?php if($row["stat9"]<=20){ ?> php progress-bar-info <?php }
                                                                elseif($row["stat9"]<=40){ ?> php progress-bar-success <?php }
                                                                elseif($row["stat9"]<=60){ ?> php progress-bar-warning <?php }
                                                                elseif($row["stat9"]<=80){ ?> php progress-bar-danger <?php }
                                                                elseif($row["stat9"]<=100){ ?> <?php } ?> 
                                                                progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="<?php echo "".$row["stat9"]."%"; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo "".$row["stat9"]."%"; ?>;"></div>
                                                            </div>
                                                        </div></label>
                                                                                                                
                                                        <label class="col-md-3 col-sm-3"><h4>Komosa - <?php echo "".$row["stat10"]." kU/I"; ?></h4></label>
                                                       <label class="col-md-7 col-sm-7">
                                                        <div class="panel-body">
                                                            <div class="progress">
                                                                <div class="progress-bar 
                                                                <?php if($row["stat10"]<=20){ ?> php progress-bar-info <?php }
                                                                elseif($row["stat10"]<=40){ ?> php progress-bar-success <?php }
                                                                elseif($row["stat10"]<=60){ ?> php progress-bar-warning <?php }
                                                                elseif($row["stat10"]<=80){ ?> php progress-bar-danger <?php }
                                                                elseif($row["stat10"]<=100){ ?> <?php } ?> 
                                                                progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="<?php echo "".$row["stat10"].""; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo "".$row["stat10"]."%"; ?>;"></div>
                                                            </div>
                                                        </div></label>
                                                                                                                
                                                        <label class="col-md-3 col-sm-3"><h4>Bylica - <?php echo "".$row["stat11"]." kU/I"; ?></h4></label>
                                                       <label class="col-md-7 col-sm-7">
                                                        <div class="panel-body">
                                                            <div class="progress">
                                                                <div class="progress-bar 
                                                                <?php if($row["stat11"]<=20){ ?> php progress-bar-info <?php }
                                                                elseif($row["stat11"]<=40){ ?> php progress-bar-success <?php }
                                                                elseif($row["stat11"]<=60){ ?> php progress-bar-warning <?php }
                                                                elseif($row["stat11"]<=80){ ?> php progress-bar-danger <?php }
                                                                elseif($row["stat11"]<=100){ ?> <?php } ?> 
                                                                progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="<?php echo "".$row["stat11"].""; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo "".$row["stat11"]."%"; ?>;"></div>
                                                            </div>
                                                        </div></label>
                                                                                                                
                                                        <label class="col-md-3 col-sm-3"><h4>Ambrozja - <?php echo "".$row["stat12"]." kU/I"; ?></h4></label>
                                                       <label class="col-md-7 col-sm-7">
                                                        <div class="panel-body">
                                                            <div class="progress">
                                                                <div class="progress-bar 
                                                                <?php if($row["stat12"]<=20){ ?> php progress-bar-info <?php }
                                                                elseif($row["stat12"]<=40){ ?> php progress-bar-success <?php }
                                                                elseif($row["stat12"]<=60){ ?> php progress-bar-warning <?php }
                                                                elseif($row["stat12"]<=80){ ?> php progress-bar-danger <?php }
                                                                elseif($row["stat12"]<=100){ ?> <?php } ?> 
                                                                progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="<?php echo "".$row["stat12"].""; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo "".$row["stat12"]."%"; ?>;"></div>
                                                            </div>
                                                        </div></label>
                                                                                                                
                                                        <label class="col-md-3 col-sm-3"><h4>Cladosporium - <?php echo "".$row["stat13"]." kU/I"; ?></h4></label>
                                                       <label class="col-md-7 col-sm-7">
                                                        <div class="panel-body">
                                                            <div class="progress">
                                                                <div class="progress-bar 
                                                                <?php if($row["stat13"]<=20){ ?> php progress-bar-info <?php }
                                                                elseif($row["stat13"]<=40){ ?> php progress-bar-success <?php }
                                                                elseif($row["stat13"]<=60){ ?> php progress-bar-warning <?php }
                                                                elseif($row["stat13"]<=80){ ?> php progress-bar-danger <?php }
                                                                elseif($row["stat13"]<=100){ ?> <?php } ?> 
                                                                progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="<?php echo "".$row["stat13"].""; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo "".$row["stat13"]."%"; ?>;"></div>
                                                            </div>
                                                        </div></label>
                                                                                                                
                                                        <label class="col-md-3 col-sm-3"><h4>Alternaria - <?php echo "".$row["stat14"]." kU/I"; ?></h4></label>
                                                       <label class="col-md-7 col-sm-7">
                                                        <div class="panel-body">
                                                            <div class="progress">
                                                                <div class="progress-bar 
                                                                <?php if($row["stat14"]<=20){ ?> php progress-bar-info <?php }
                                                                elseif($row["stat14"]<=40){ ?> php progress-bar-success <?php }
                                                                elseif($row["stat14"]<=60){ ?> php progress-bar-warning <?php }
                                                                elseif($row["stat14"]<=80){ ?> php progress-bar-danger <?php }
                                                                elseif($row["stat14"]<=100){ ?> <?php } ?> 
                                                                progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="<?php echo "".$row["stat14"].""; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo "".$row["stat14"]."%"; ?>;"></div>
                                                            </div>
                                                        </div></label>
                
                                                    <?php
                                                    }
                                                } else 
                                                {
                                                    echo "<p> - Brak analizy.</p><br/>";                                 
                                                }
                                                ?>
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
                                <li><a href="panel_doctor.php"><i class="fa fa-circle"></i>Panel lekarza</a></li>
                                <li><a href="stat.php"><i class="fa fa-circle"></i>Statystyki pacjentów</a></li>
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
