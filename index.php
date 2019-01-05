<?php
    session_start(); 

    if(isset($_SESSION['zalogowany'])&&($_SESSION['zalogowany']==true))       //jesli zaloguje poprawnie przenosi do pliku panel.php
    {
        header('Location: panel_pacjent.php');
        exit();
    }


   if(isset($_POST['mail']))  //isset sprawdza czy mamy ustawioną zmienną, do rejestracji
    {
         
       $wszystko_ok=true;  //udana walidacja
       $nick=$_POST['nick'];     //sprawdzenie loginu
       
       //sprawdzenie dlugosci nicka funkcja 'strlen'
       if((strlen($nick)<3) || (strlen($nick)>16))
       {
           $wszystko_ok=false;
           $_SESSION['error_nick']='<div class="col-sm-12 alert alert-info">Login musi posiadać od 3 do 16 znaków!</div>';
       }
       if(ctype_alnum($nick)==false) //ctype_alnum oznacza ze moga byc uzywane tylko znaki alfanumeryczne
       {
           $wszystko_ok=false;
           $_SESSION['error_nick']='<div class="col-sm-12 alert alert-info">Nick może składać się tylko z liter i cyfr (bez polskich znaków)!</div>';
       }
       //Sprawdzenie poprowanosci maila
       $email=$_POST['mail'];
       $emailB=filter_var($email, FILTER_SANITIZE_EMAIL); //sanityzacja kodu czyli oczyszczenie go z zlych znakow odpowiada za to filter_var, stała FILTER_SANITIZE_EMAIL odpowiada za usunięcie niedozwolonych znaków
       if((filter_var($emailB,FILTER_VALIDATE_EMAIL)==false)||($emailB!=$email)) // || to znaczy lub, stała FILTER_VALIDATE_EMAIL waliduje adres mailu
       {  // != oznacza jest różny
           $wszystko_ok=false;
           $_SESSION['error_mail']='<div class="col-sm-12 alert alert-info">Podaj poprawny adres e-mail!</div>';
       }
       //sprawdzenie poprawnosci hasla
       $haslo1=$_POST['haslo1'];
       $haslo2=$_POST['haslo2'];
       if((strlen($haslo1)<6)||(strlen($haslo1)>20))
       {
           $wszystko_ok=false;
           $_SESSION['error_haslo']='<div class="col-sm-12 alert alert-info">Hasło musi posiadać od 6 do 20 znaków!</div>';
       }
       if($haslo1!=$haslo2)
       {
           $wszystko_ok=false;
           $_SESSION['error_haslo']='<div class="col-sm-12 alert alert-info">Podane hasła nie są identyczne!</div>';
       }
       $haslo_hash=password_hash($haslo1,PASSWORD_DEFAULT); //hasowanie hasla, PASSWORD_DEFULT okresla sposob hasowania, najlepiej jejgo wlasnie uzywać bo domyslnie uzywa najlepszego
       
       // Sprawdzenie zaakceptowania regulaminu
       $regulamin=$_POST['regulamin'];
       if(!isset($_POST['regulamin'])) // ! neguje
       {
           $wszystko_ok=false;
           $_SESSION['error_rules']='<div class="col-sm-12 alert alert-info">Zaakceptuj regulamin!</div>';
       }
       
       // Sprawdzenie czy recaptch jest zaznaczona
       $secret="6LdlvHsUAAAAAFZdc-XVmrPnTxVDBK3W0R9ajNSr"; // secret kod z recaptcha
       $sprawdz=file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);   //sprawdzanie od gugla czy zostal wcisniety guzik recaptcha
       $odpowiedz=json_decode($sprawdz);
       if($odpowiedz->success==false)
       {
           $wszystko_ok=false;
           $_SESSION['error_bot']='<div class="col-sm-12 alert alert-info">Potwierdź czy nie jestes botem!</div>';
       }
       
       $imie=$_POST['imie'];     //sprawdzenie imie
       if(strlen($imie)==0)
       {
           $wszystko_ok=false;
           $_SESSION['error_imie']='<div class="col-sm-12 alert alert-info">Wpisz swoje imie!</div>';
       }
       
       $nazwisko=$_POST['nazwisko'];     //sprawdzenie nazwisko
       if(strlen($nazwisko)==0)
       {
           $wszystko_ok=false;
           $_SESSION['error_nazwisko']='<div class="col-sm-12 alert alert-info">Wpisz swoje nazwisko!</div>';
       }
       
       $adres=$_POST['adres'];     //sprawdzenie adresu
       if(strlen($adres)==0)
       {
           $wszystko_ok=false;
           $_SESSION['error_adres']='<div class="col-sm-12 alert alert-info">Wpisz swoj adres zamieszkania!</div>';
       }

       $miasto=$_POST['miasto'];     //sprawdzenie miasto
       if(strlen($miasto)==0)
       {
           $wszystko_ok=false;
           $_SESSION['error_miasto']='<div class="col-sm-12 alert alert-info">Wpisz swoje miasto zamieszkania!</div>';
       }
       
       $telefon=$_POST['telefon'];     //sprawdzenie telefon
       if(strlen($telefon)==0)
       {
           $wszystko_ok=false;
           $_SESSION['error_telefon']='<div class="col-sm-12 alert alert-info">Wpisz swoj telefon!</div>';
       }
       elseif(strlen($telefon)<9)
       {
           $wszystko_ok=false;
           $_SESSION['error_telefon']='<div class="col-sm-12 alert alert-info">Nie prawidłowy numer telefonu!</div>';
       }
       elseif(strlen($telefon)>9)
       {
           $wszystko_ok=false;
           $_SESSION['error_telefon']='<div class="col-sm-12 alert alert-info">Nie prawidłowy numer telefonu!</div>';
       }    
       $status='PACJENT';  //dodawanie PACJENT do statusu dla kazdego uzytkownika
       //Zapamietanie danych wpisywanych do pol
       $_SESSION['fr_nick']=$nick;
       $_SESSION['fr_email']=$email;
       $_SESSION['fr_haslo1']=$haslo1;
       $_SESSION['fr_haslo2']=$haslo2;
       $_SESSION['fr_imie']=$imie;
       $_SESSION['fr_nazwisko']=$nazwisko;
       $_SESSION['fr_adres']=$adres;
       $_SESSION['fr_miasto']=$miasto;
       $_SESSION['fr_telefon']=$telefon;
       if(isset($_POST['regulamin']))$_SESSION['fr_regulamin']=true;

       //Polaczenie z baza    
       require_once"connect.php";
       mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); //raportujemy tylko wyjatki czyli exception
       try
       {
            $polaczenie=new mysqli($host, $db_user, $db_password, $db_name);  
            if($polaczenie->connect_errno!=0)//wypelni sie jezeli bedzie blad polaczenia
            {
                throw new exception(mysqli_connect_errno); // oznacza rzuc nowym wyjatkiem
            }
            else //wypelni sie jesli wykona sie polaczenie do bazy
            {
                //Sprawdzenie czy email jest juz w bazie
                $rezultat=$polaczenie->query("SELECT ID FROM users WHERE email='$email'");
                if(!$rezultat)throw new exception($polaczenie->error); // ! neguje
                $ile_takich_maili=$rezultat->num_rows;
                if($ile_takich_maili>0)
                {
                    $wszystko_ok=false;
                    $_SESSION['error_mail']='<div class="col-sm-12 alert alert-info">Istnieje juz konto o takim adresie Email!</div>';
                }
                //Sprawdzenie czy login jest juz w bazie
                $rezultat=$polaczenie->query("SELECT ID FROM users WHERE user='$nick'");
                if(!$rezultat)throw new exception($polaczenie->error); // ! neguje
                $ile_takich_nickow=$rezultat->num_rows;
                if($ile_takich_nickow>0)
                {
                    $wszystko_ok=false;
                    $_SESSION['error_nick']='<div class="col-sm-12 alert alert-info">Istnieje juz konto o takim loginie!</div>';
                } 
                if($wszystko_ok==true) //wszystkie testy git, dodajemy do bazy 
                {
                    if($polaczenie->query("INSERT INTO users(ID, user, pass, email, imie, nazwisko, adres, miasto, tel, status) VALUES (NULL, '$nick', '$haslo_hash', '$email','$imie','$nazwisko','$adres','$miasto','$telefon','$status')"))
                    {
                        $_SESSION['udanarejestracja']=true;
                        header('Location: witamy.php');
                    }
                    else
                    {
                        throw new exception($polaczenie->error);
                    }
                }
                $polaczenie->close();  
            }
       }
       catch(Exception $e) //złap wyjątki
       {
            echo'<div class="error">Błąd serwera! </div>';
            echo '<span style="color:red;">Przepraszamy za niedogodności.</span>';
            echo '<br/><br/>Informacja developerska: '.$e;
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
    <section id="banner" class="banner">
        <div class="bg-color">
            <nav class="navbar navbar-default navbar-fixed-top">
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
                                <li class="active"><a href="#banner">Strona główna</a></li>
                                <li class=""><a href="#service">Usługi</a></li>
                                <li class=""><a href="#about">Nasz system</a></li>
                                <!--<li class=""><a href="#testimonial">Opinie pacjentów</a></li> -->
                                <li class=""><a href="#panel">Panel pacjenta</a></li>
                                <li class=""><a href="#registration">Rejestracja</a></li>
                                <li class=""><a href="#footer">Kontakt</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
            <div class="container">
                <div class="row">
                    <div class="banner-info">
                        <div class="banner-logo text-center">
                            <img src="img/logo.png" class="img-responsive">
                        </div>
                        <div class="banner-text text-center">
                            <br/><br/><br/><br/><h3 class="white no-padding" >Jakis napis cos tu bedzie <br> ale nie wiem co.</h3>
                        </div>
                        <div class="overlay-detail text-center">
                            <a href="#service"><i class="fa fa-angle-down"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--/navbar-->
    
    
    <!--services-->
    <section id="service" class="section-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-sm-4">
                    <h2 class="ser-title">Usługi</h2>
                    <hr class="botm-line">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris cillum.</p>
                </div>
                <div class="col-md-4 col-sm-4">
                    <div class="service-info">
                        <div class="icon">
                            <i class="fa fa-stethoscope"></i>
                        </div>
                        <div class="icon-info">
                            <h4>Badania Alergiczne</h4>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                        </div>
                    </div>
                    <div class="service-info">
                        <div class="icon">
                            <i class="fa fa-hospital-o"></i>
                        </div>
                        <div class="icon-info">
                            <h4>Gabinet odczulań</h4>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4">
                    <div class="service-info">
                        <div class="icon">
                            <i class="fa fa-user-md"></i>
                        </div>
                        <div class="icon-info">
                            <h4>Konsultacje medyczne</h4>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                        </div>
                    </div>
                    <div class="service-info">
                        <div class="icon">
                            <i class="fa fa-medkit"></i>
                        </div>
                        <div class="icon-info">
                            <h4>Leczenie chorób alergicznych</h4>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--/services-->
    
    
    <!--news-->
    <section id="cta-1" class="section-padding">
        <div class="container">
            <div class="row">
                <div class="schedule-tab">
                    <div class="col-md-4 col-sm-4 bor-left">
                        <div class="mt-boxy-color"></div>
                        <div class="medi-info">
                            <h3>Aktualności - 25.11.2018</h3>
                            <p>I am text block. Edit this text from Appearance / Customize / Homepage header columns. Lorem ipsum dolor sit amet, consectetur adipiscing elit. </p>
                            <a href="#" class="medi-info-btn">Przeczytaj więcej</a>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="medi-info">
                            <h3>Aktualności - 04.09.201</h3>
                            <p>I am text block. Edit this text from Appearance / Customize / Homepage header columns. Lorem ipsum dolor sit amet, consectetur adipiscing elit. </p>
                            <a href="#" class="medi-info-btn">Przeczytaj więcej</a>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 mt-boxy-3">
                        <div class="mt-boxy-color"></div>
                        <div class="time-info">
                            <h3>Godziny otwarcia</h3>
                            <table style="margin: 8px 0px 0px;" border="1">
                                <tbody>
                                    <tr>
                                        <td>Poniedziałek - Piątek</td>
                                        <td>8.00 - 15.00</td>
                                    </tr>
                                    <tr>
                                        <td>Sobota</td>
                                        <td>nieczynne</td>
                                    </tr>
                                    <tr>
                                        <td>Niedziela</td>
                                        <td>nieczynne</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--news-->
    
    
    <!--about-->
    <section id="about" class="section-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <div class="section-title">
                        <h2 class="head-title lg-line">Poznaj nasz system medyczny</h2>
                        <hr class="botm-line">
                        <p class="sec-para">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua..</p>
                        <a href="" style="color: #0cb8b6; padding-top:10px;">Know more..</a>
                    </div>
                </div>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <div style="visibility: visible;" class="col-sm-9 more-features-box">
                        <div class="more-features-box-text">
                            <div class="more-features-box-text-icon"> <i class="fa fa-angle-right" aria-hidden="true"></i> </div>
                            <div class="more-features-box-text-description">
                                <h3>Panel pacjenta</h3>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et. Ut wisi enim ad minim veniam, quis nostrud.</p>
                            </div>
                        </div>
                        <div class="more-features-box-text">
                            <div class="more-features-box-text-icon"> <i class="fa fa-angle-right" aria-hidden="true"></i> </div>
                            <div class="more-features-box-text-description">
                                <h3>Profesjonalna analiza wyników badań</h3>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et. Ut wisi enim ad minim veniam, quis nostrud.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--/ about-->

   
    <!--panel-->
    <section id="panel" class="section-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="ser-title">Panel pacjenta</h2>
                    <hr class="botm-line">
                </div>
                <div class="col-md-8 col-sm-8 marb20">
                    <div class="contact-info">
                        <h3 class="cnt-ttl">Zaloguj się do panelu użytkownika.</h3>
                        <div class="space"></div>
                        <div id="sendmessage">Your message has been sent. Thank you!</div>
                        <div id="errormessage"></div>
                        <form action="zaloguj.php" method="post">
                            <div class="form-group">
                                <label class="control-label col-sm-2">Login:</label>
                                <div class="col-sm-9"><input type="text" name="login" class="form-control" id="login" placeholder="Twój login" data-rule="minlen:4" />
                                <div class="validation"></div></div>
                            </div>
                            <label class="control-label col-md-12"></label>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Hasło:</label>
                                <div class="col-sm-9"><input type="password" name="haslo" class="form-control" id="login" placeholder="Twoje hasło" data-rule="minlen:4" />
                                <div class="validation"></div></div> 
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2"></label>     
                                <label class="control-label col-md-12"></label>
                                <input type="submit" class="col-sm-2 btn btn-form" value="Zaloguj się" />
                            </div>        
                        </form>
                        <?php
                            if(isset($_SESSION['blad']))
                            {
                                echo $_SESSION['blad']; 
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--/ panel-->

   
    <!--cta 2-->
    <section id="cta-2" class="section-padding">
        <div class="container">
            <div class=" row">
                <div class="col-md-2"></div>
                <div class="text-right-md col-md-4 col-sm-4">
                    <h2 class="section-title white lg-line">« Nie masz konta?<br> « Zarejestruj się już teraz!</h2>
                </div>
                <div class="col-md-4 col-sm-5">
                    Tworząc u nas konto masz możliwość przejżeć swoje wyniki badań, opinie lekarza czy zapisać się na najbliższe badanie. TO TRZEBA POPRAWIC
                    <p class="text-right text-primary"><i>— Poradnia Alergologiczna</i></p>
                </div>
                <div class="col-md-2"></div>
            </div>
        </div>
    </section>
    <!--cta-->

   
    <!--registration-->
    <section id="registration" class="section-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="ser-title">Rejestracja</h2>
                    <hr class="botm-line">
                </div>
                <div class="col-md-8 col-sm-8 marb20">
                    <div class="contact-info">
                        <h3 class="cnt-ttl">Wpisz swoje dane w celu zarejestrowania się do panelu pacjenta.</h3>
                        <div class="space"></div>
 
                        <form method="post">
            <label class="control-label col-sm-2" for="email">Login:</label>
            <div class="col-sm-9"><input type="text" value="<?php
            if(isset($_SESSION['fr_nick']))
            {
                echo $_SESSION['fr_nick'];
                unset($_SESSION['fr_nick']);
            }   
            ?>" name="nick" class="form-control " id="name" placeholder="Login" data-rule="minlen:4" /></div>        
            <?php
            if(isset($_SESSION['error_nick']))
            {
                echo'<div class="error">'.$_SESSION['error_nick'].'</div>';  
                unset($_SESSION['error_nick']); 
            }
            ?>
            <label class="control-label col-md-12"></label>
            <label class="control-label col-sm-2" for="email">Email:</label>               
            <div class="col-sm-9"><input type="text" value="<?php
            if(isset($_SESSION['fr_email']))
            {
                echo $_SESSION['fr_email'];
                unset($_SESSION['fr_email']);
            }   
            ?>" name="mail" class="form-control" id="name" placeholder="Email" data-rule="minlen:4" /></div>
            <?php
            if(isset($_SESSION['error_mail']))
            {
                echo'<div class="error">'.$_SESSION['error_mail'].'</div>'; 
                unset($_SESSION['error_mail']);
            }
            ?>
            <label class="control-label col-md-12"></label>
            <label class="control-label col-sm-2" for="email">Hasło:</label>                              
            <div class="col-sm-9"><input type="password" value="<?php
            if(isset($_SESSION['fr_haslo1']))
            {
                echo $_SESSION['fr_haslo1'];
                unset($_SESSION['fr_haslo1']);
            }   
            ?>" name="haslo1" class="form-control" id="name" placeholder="Hasło" data-rule="minlen:4" ></div>
            <?php
            if(isset($_SESSION['error_haslo']))
            {
                echo'<div class="error">'.$_SESSION['error_haslo'].'</div>';
                unset($_SESSION['error_haslo']); 
            }
            ?>
            <label class="control-label col-md-12"></label>
            <label class="control-label col-sm-2"></label>                              
            <div class="col-sm-9"><input type="password" value="<?php
            if(isset($_SESSION['fr_haslo2']))
            {
                echo $_SESSION['fr_haslo2'];
                unset($_SESSION['fr_haslo2']);
            }   
            ?>" name="haslo2" class="form-control" id="name" placeholder="Powtórz hasło" data-rule="minlen:4" /></div>
            <label class="control-label col-md-12"></label>
            <label class="control-label col-sm-2" for="email">Imię:</label>                              
            <div class="col-sm-9"><input type="text" value="<?php
            if(isset($_SESSION['fr_imie']))
            {
                echo $_SESSION['fr_imie'];
                unset($_SESSION['fr_imie']);
            }
            ?>" name="imie" class="form-control" id="imie" placeholder="Imię" data-rule="minlen:4" /></div>     
            <?php
            if(isset($_SESSION['error_imie']))
            {
                echo'<div class="error">'.$_SESSION['error_imie'].'</div>';
                unset($_SESSION['error_imie']); 
            }
            ?>
            <label class="control-label col-md-12"></label>
            <label class="control-label col-sm-2">Nazwisko:</label>                              
            <div class="col-sm-9"><input type="text" value="<?php
            if(isset($_SESSION['fr_nazwisko']))
            {
                echo $_SESSION['fr_nazwisko'];
                unset($_SESSION['fr_nazwisko']);
            }
            ?>" name="nazwisko" class="form-control" id="nazwisko" placeholder="Nazwisko" data-rule="minlen:4" /></div>             
            <?php
            if(isset($_SESSION['error_nazwisko']))
            {
                echo'<div class="error">'.$_SESSION['error_nazwisko'].'</div>';  
                unset($_SESSION['error_nazwisko']); 
            }
            ?>
            <label class="control-label col-md-12"></label>
            <label class="control-label col-sm-2">Adres:</label>                              
            <div class="col-sm-9"><input type="text" value="<?php
            if(isset($_SESSION['fr_adres']))
            {
                echo $_SESSION['fr_adres'];
                unset($_SESSION['fr_adres']);
            }
            ?>" name="adres" class="form-control" id="adres" placeholder="Adres nr.domu/nr.mieszkania" data-rule="minlen:4" /></div>          
            <?php
            if(isset($_SESSION['error_adres']))
            {
                echo'<div class="error">'.$_SESSION['error_adres'].'</div>'; 
                unset($_SESSION['error_adres']); 
            }
            ?>
            <label class="control-label col-md-12"></label>
            <label class="control-label col-sm-2">Miasto:</label>                              
            <div class="col-sm-9"><input type="text" value="<?php
            if(isset($_SESSION['fr_miasto']))
            {
                echo $_SESSION['fr_miasto'];
                unset($_SESSION['fr_miasto']);
            }
            ?>" name="miasto" class="form-control" id="miasto" placeholder="Miasto, kod pocztowy" data-rule="minlen:4" /></div>       
            <?php
            if(isset($_SESSION['error_miasto']))
            {
            echo'<div class="error">'.$_SESSION['error_miasto'].'</div>';  
            unset($_SESSION['error_miasto']); 
            }
            ?>
            <label class="control-label col-md-12"></label>
            <label class="control-label col-sm-2">Telefon:</label>                              
            <div class="col-sm-9"><input type="text" value="<?php
            if(isset($_SESSION['fr_telefon']))
            {
                echo $_SESSION['fr_telefon'];
                unset($_SESSION['fr_telefon']);
            }
            ?>" name="telefon" class="form-control" id="telefon" placeholder="Telefon" data-rule="minlen:4" /></div>        
            <?php
            if(isset($_SESSION['error_telefon']))
            {
            echo'<div class="error">'.$_SESSION['error_telefon'].'</div>';  
            unset($_SESSION['error_telefon']); 
            }
            ?>
            <label class="control-label col-md-12"></label>            
            <label class="control-label col-sm-2"></label>                              
            <div class="col-sm-10"><label><input type='checkbox' name='regulamin' <?php 
            if(isset($_SESSION['fr_regulamin'])) 
            { 
                echo "checked" ; 
                unset($_SESSION['fr_regulamin']); 
            } 
            ?>/>Akceptuje regulamin</label></div>  
            <?php
            if(isset($_SESSION['error_rules']))
            {
                echo'<div class="error">'.$_SESSION['error_rules'].'</div>'; 
                unset($_SESSION['error_rules']); 
            }
            ?>
            <label class="control-label col-md-12"></label>
            <label class="control-label col-sm-2"></label>                              
            <div class="col-sm-10"><div class="g-recaptcha" data-sitekey="6LdlvHsUAAAAADExIyqT5uvSyopnzyfW7li5ZVlX"></div>
            <?php
            if(isset($_SESSION['error_bot']))
            {
                echo'<div class="error">'.$_SESSION['error_bot'].'</div>';  
                unset($_SESSION['error_bot']);
            }
            ?></div> 
            <label class="control-label col-md-12"></label>
            <label class="control-label col-sm-2"></label>                               
            <div class="form-action">
            <div class="col-sm-10"><button type="submit" class="btn btn-form">Zarejestruj</button>
            <?php
            if(isset($_SESSION['yesregistration']))
            {
                echo'<div class="alert">'.$_SESSION['yesregistration'].'</div>';
                unset($_SESSION['yesregistration']);
            }     
            ?></div>
            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /registration-->

   
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
                                <li><a href="index.php"><i class="fa fa-circle"></i>Strona głowna</a></li>
                                <li><a href="#service"><i class="fa fa-circle"></i>Usługi</a></li>
                                <li><a href="#about"><i class="fa fa-circle"></i>Nasz system</a></li>
                                <li><a href="#panel"><i class="fa fa-circle"></i>Panel pacjenta</a></li>
                                <li><a href="#registration"><i class="fa fa-circle"></i>Rejestracja</a></li>
                                <li><a href="#footer"><i class="fa fa-circle"></i>Kontakt</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 marb20">
                        <div class="ftr-tle">
                            <h4 class="white no-padding">Znajdź nas</h4>
                        </div>
                        <div class="info-sec ">
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
                        © Copyright Poradnia Alergologiczna. All Rights Reserved
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
