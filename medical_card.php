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
    if(isset($_POST['submit'])) //  do dodawania analizy
    {
        $example=htmlspecialchars($_POST['example']);
        $data=htmlspecialchars($_POST['data']);
        require_once"connect.php";
        $conn = new mysqli($host, $db_user, $db_password, $db_name);
        if ($conn->connect_error) 
        {
            printf("Connection failed: " . $conn->connect_error);
            exit();
        }
        $sql22="UPDATE users SET karta = CONCAT(karta, ' <br/><br/> ' , ' $data ' ,' <br/> ', ' $example ' ) WHERE imie= '".$_SESSION['imieview']."' AND nazwisko= '".$_SESSION['nazwiskoview']."' ";
        if ($conn->query($sql22) === TRUE) 
        {
            $_SESSION['good']='<div class="col-sm-12 alert alert-success">Dane zostały dodane prawidłowo!</div>';        
        } 
        else 
        {
            $_SESSION['error1']='<div class="col-sm-12 alert alert-info">Błąd wprowadż dane ponownie!</div>';
        }
        $conn->close();
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
    <script>// odswieze strone raz
        if(!location.search)setTimeout("location.replace(location.href+'?+')",10)
    </script>
   


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
                        <h2 class="head-title lg-line col-sm-10">KARTA BADAŃ DLA PACJENTA: <mark>
                                <?php echo $_SESSION['imieview']." ".$_SESSION['nazwiskoview'].""?></mark></h2>
                        <a href="panel_doctor.php" class="btn btn-primary btn-lg active col-sm-2" role="button" aria-pressed="true">WSTECZ</a>
                        <hr class="botm-line">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div style="visibility: visible;" class="col-sm-12 more-features-box">
                                <div class="more-features-box-text h4">

                                    <div class="panel-body">
                                        <!-- Nav tabs -->
                                        <ul class="nav nav-tabs">
                                            <li class="active"><a href="#home" data-toggle="tab">Wyniki badań</a>
                                            </li>
                                            <li><a href="#profile" data-toggle="tab">Analiza</a>
                                            </li>
                                            <li><a href="#messages" data-toggle="tab">Wnioski lekarza</a>
                                            </li>
                                            <li><a href="#other" data-toggle="tab">Ogólna analiza pacjentów</a>
                                            </li>
                                        </ul>
                                        <!-- Tab panes -->
                                        <div class="tab-content">
                                            <div class="tab-pane fade in active" id="home"> <!-- WYNIKI BADAN @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->
                                                <?php
                                                require_once"connect.php";
                                                $conn = new mysqli($host, $db_user, $db_password, $db_name);
                                                if ($conn->connect_error) 
                                                {
                                                    printf("Connection failed: " . $conn->connect_error);
                                                    exit();
                                                }
                                                $sql = "SELECT analiza, data, leszczyna, olsza, brzoza, topola, dab, trawy, babka_lancetowata, szczaw, pokrzywa, komosa, bylica, ambrozja, cladosporium, alternaria FROM ige, users WHERE ID_user=ID AND imie='".$_SESSION['imieview']."' AND nazwisko='".$_SESSION['nazwiskoview']."' ORDER BY data DESC ";
                                                $result = $conn->query($sql);
                                                if ($result->num_rows > 0) 
                                                {
                                                    if ($result->num_rows > 0) 
                                                    {
                                                    while($row = $result->fetch_assoc()) 
                                                    {
                                                        if($row["alternaria"]<0.3){$_SESSION['alt']='0';}
                                                        if($row["alternaria"]>=0.3 && $row["alternaria"]<=0.7){$_SESSION['alt']='1';}
                                                        if($row["alternaria"]>0.7 && $row["alternaria"]<=3.5){$_SESSION['alt']='2';}
                                                        if($row["alternaria"]>3.5 && $row["alternaria"]<=17.5){$_SESSION['alt']='3';}
                                                        if($row["alternaria"]>17.5 && $row["alternaria"]<=50){$_SESSION['alt']='4';}
                                                        if($row["alternaria"]>50 && $row["alternaria"]<=100){$_SESSION['alt']='5';}
                                                        if($row["alternaria"]>100){$_SESSION['alt']='6';}  
                                                        
                                                        if($row["leszczyna"]<0.3){$_SESSION['le']='0';}
                                                        if($row["leszczyna"]>=0.3 && $row["leszczyna"]<=0.7){$_SESSION['le']='1';}
                                                        if($row["leszczyna"]>0.7 && $row["leszczyna"]<=3.5){$_SESSION['le']='2';}
                                                        if($row["leszczyna"]>3.5 && $row["leszczyna"]<=17.5){$_SESSION['le']='3';}
                                                        if($row["leszczyna"]>17.5 && $row["leszczyna"]<=50){$_SESSION['le']='4';}
                                                        if($row["leszczyna"]>50 && $row["leszczyna"]<=100){$_SESSION['le']='5';}
                                                        if($row["leszczyna"]>100){$_SESSION['le']='6';}
                                                        
                                                        if($row["olsza"]<0.3){$_SESSION['ol']='0';}
                                                        if($row["olsza"]>=0.3 && $row["olsza"]<=0.7){$_SESSION['ol']='1';}
                                                        if($row["olsza"]>0.7 && $row["olsza"]<=3.5){$_SESSION['ol']='2';}
                                                        if($row["olsza"]>3.5 && $row["olsza"]<=17.5){$_SESSION['ol']='3';}
                                                        if($row["olsza"]>17.5 && $row["olsza"]<=50){$_SESSION['ol']='4';}
                                                        if($row["olsza"]>50 && $row["olsza"]<=100){$_SESSION['ol']='5';}
                                                        if($row["olsza"]>100){$_SESSION['ol']='6';} 
                                                        
                                                        if($row["brzoza"]<0.3){$_SESSION['br']='0';}
                                                        if($row["brzoza"]>=0.3 && $row["brzoza"]<=0.7){$_SESSION['br']='1';}
                                                        if($row["brzoza"]>0.7 && $row["brzoza"]<=3.5){$_SESSION['br']='2';}
                                                        if($row["brzoza"]>3.5 && $row["brzoza"]<=17.5){$_SESSION['br']='3';}
                                                        if($row["brzoza"]>17.5 && $row["brzoza"]<=50){$_SESSION['br']='4';}
                                                        if($row["brzoza"]>50 && $row["brzoza"]<=100){$_SESSION['br']='5';}
                                                        if($row["brzoza"]>100){$_SESSION['br']='6';} 
                                                        
                                                        if($row["topola"]<0.3){$_SESSION['to']='0';}
                                                        if($row["topola"]>=0.3 && $row["topola"]<=0.7){$_SESSION['to']='1';}
                                                        if($row["topola"]>0.7 && $row["topola"]<=3.5){$_SESSION['to']='2';}
                                                        if($row["topola"]>3.5 && $row["topola"]<=17.5){$_SESSION['to']='3';}
                                                        if($row["topola"]>17.5 && $row["topola"]<=50){$_SESSION['to']='4';}
                                                        if($row["topola"]>50 && $row["topola"]<=100){$_SESSION['to']='5';}
                                                        if($row["topola"]>100){$_SESSION['to']='6';} 
                                                        
                                                        if($row["dab"]<0.3){$_SESSION['da']='0';}
                                                        if($row["dab"]>=0.3 && $row["dab"]<=0.7){$_SESSION['da']='1';}
                                                        if($row["dab"]>0.7 && $row["dab"]<=3.5){$_SESSION['da']='2';}
                                                        if($row["dab"]>3.5 && $row["dab"]<=17.5){$_SESSION['da']='3';}
                                                        if($row["dab"]>17.5 && $row["dab"]<=50){$_SESSION['da']='4';}
                                                        if($row["dab"]>50 && $row["dab"]<=100){$_SESSION['da']='5';}
                                                        if($row["dab"]>100){$_SESSION['da']='6';} 
                                                        
                                                        if($row["trawy"]<0.3){$_SESSION['tr']='0';}
                                                        if($row["trawy"]>=0.3 && $row["trawy"]<=0.7){$_SESSION['tr']='1';}
                                                        if($row["trawy"]>0.7 && $row["trawy"]<=3.5){$_SESSION['tr']='2';}
                                                        if($row["trawy"]>3.5 && $row["trawy"]<=17.5){$_SESSION['tr']='3';}
                                                        if($row["trawy"]>17.5 && $row["trawy"]<=50){$_SESSION['tr']='4';}
                                                        if($row["trawy"]>50 && $row["trawy"]<=100){$_SESSION['tr']='5';}
                                                        if($row["trawy"]>100){$_SESSION['tr']='6';} 
                                                        
                                                        if($row["babka_lancetowata"]<0.3){$_SESSION['bab']='0';}
                                                        if($row["babka_lancetowata"]>=0.3 && $row["babka_lancetowata"]<=0.7){$_SESSION['bab']='1';}
                                                        if($row["babka_lancetowata"]>0.7 && $row["babka_lancetowata"]<=3.5){$_SESSION['bab']='2';}
                                                        if($row["babka_lancetowata"]>3.5 && $row["babka_lancetowata"]<=17.5){$_SESSION['bab']='3';}
                                                        if($row["babka_lancetowata"]>17.5 && $row["babka_lancetowata"]<=50){$_SESSION['bab']='4';}
                                                        if($row["babka_lancetowata"]>50 && $row["babka_lancetowata"]<=100){$_SESSION['bab']='5';}
                                                        if($row["babka_lancetowata"]>100){$_SESSION['bab']='6';} 
                                                        
                                                        if($row["szczaw"]<0.3){$_SESSION['sz']='0';}
                                                        if($row["szczaw"]>=0.3 && $row["szczaw"]<=0.7){$_SESSION['sz']='1';}
                                                        if($row["szczaw"]>0.7 && $row["szczaw"]<=3.5){$_SESSION['sz']='2';}
                                                        if($row["szczaw"]>3.5 && $row["szczaw"]<=17.5){$_SESSION['sz']='3';}
                                                        if($row["szczaw"]>17.5 && $row["szczaw"]<=50){$_SESSION['sz']='4';}
                                                        if($row["szczaw"]>50 && $row["szczaw"]<=100){$_SESSION['sz']='5';}
                                                        if($row["szczaw"]>100){$_SESSION['sz']='6';} 
                                                        
                                                        if($row["pokrzywa"]<0.3){$_SESSION['pok']='0';}
                                                        if($row["pokrzywa"]>=0.3 && $row["pokrzywa"]<=0.7){$_SESSION['pok']='1';}
                                                        if($row["pokrzywa"]>0.7 && $row["pokrzywa"]<=3.5){$_SESSION['pok']='2';}
                                                        if($row["pokrzywa"]>3.5 && $row["pokrzywa"]<=17.5){$_SESSION['pok']='3';}
                                                        if($row["pokrzywa"]>17.5 && $row["pokrzywa"]<=50){$_SESSION['pok']='4';}
                                                        if($row["pokrzywa"]>50 && $row["pokrzywa"]<=100){$_SESSION['pok']='5';}
                                                        if($row["pokrzywa"]>100){$_SESSION['pok']='6';} 
                                                        
                                                        if($row["komosa"]<0.3){$_SESSION['ko']='0';}
                                                        if($row["komosa"]>=0.3 && $row["komosa"]<=0.7){$_SESSION['ko']='1';}
                                                        if($row["komosa"]>0.7 && $row["komosa"]<=3.5){$_SESSION['ko']='2';}
                                                        if($row["komosa"]>3.5 && $row["komosa"]<=17.5){$_SESSION['ko']='3';}
                                                        if($row["komosa"]>17.5 && $row["komosa"]<=50){$_SESSION['ko']='4';}
                                                        if($row["komosa"]>50 && $row["komosa"]<=100){$_SESSION['ko']='5';}
                                                        if($row["komosa"]>100){$_SESSION['ko']='6';} 
                                                        
                                                        if($row["bylica"]<0.3){$_SESSION['by']='0';}
                                                        if($row["bylica"]>=0.3 && $row["bylica"]<=0.7){$_SESSION['by']='1';}
                                                        if($row["bylica"]>0.7 && $row["bylica"]<=3.5){$_SESSION['by']='2';}
                                                        if($row["bylica"]>3.5 && $row["bylica"]<=17.5){$_SESSION['by']='3';}
                                                        if($row["bylica"]>17.5 && $row["bylica"]<=50){$_SESSION['by']='4';}
                                                        if($row["bylica"]>50 && $row["bylica"]<=100){$_SESSION['by']='5';}
                                                        if($row["bylica"]>100){$_SESSION['by']='6';} 
                                                        
                                                        if($row["ambrozja"]<0.3){$_SESSION['am']='0';}
                                                        if($row["ambrozja"]>=0.3 && $row["ambrozja"]<=0.7){$_SESSION['am']='1';}
                                                        if($row["ambrozja"]>0.7 && $row["ambrozja"]<=3.5){$_SESSION['am']='2';}
                                                        if($row["ambrozja"]>3.5 && $row["ambrozja"]<=17.5){$_SESSION['am']='3';}
                                                        if($row["ambrozja"]>17.5 && $row["ambrozja"]<=50){$_SESSION['am']='4';}
                                                        if($row["ambrozja"]>50 && $row["ambrozja"]<=100){$_SESSION['am']='5';}
                                                        if($row["ambrozja"]>100){$_SESSION['am']='6';} 
                                                        
                                                        if($row["cladosporium"]<0.3){$_SESSION['cl']='0';}
                                                        if($row["cladosporium"]>=0.3 && $row["cladosporium"]<=0.7){$_SESSION['cl']='1';}
                                                        if($row["cladosporium"]>0.7 && $row["cladosporium"]<=3.5){$_SESSION['cl']='2';}
                                                        if($row["cladosporium"]>3.5 && $row["cladosporium"]<=17.5){$_SESSION['cl']='3';}
                                                        if($row["cladosporium"]>17.5 && $row["cladosporium"]<=50){$_SESSION['cl']='4';}
                                                        if($row["cladosporium"]>50 && $row["cladosporium"]<=100){$_SESSION['cl']='5';}
                                                        if($row["cladosporium"]>100){$_SESSION['cl']='6';} 
                                                        
                                                        echo "<h3>Badanie z dnia:&nbsp;<mark>".$row["data"]."</mark></h3>";
                                                        echo "<table><tr><th>Alergeny</th><th>Stężenia &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th><th>Klasa</th></tr>"; 
                                                        echo "<tr><td>- Leszczyna</td><td>".$row["leszczyna"]." kU/I</td><td>".$_SESSION["le"]."</td></tr>";
                                                         
                                                        echo "<tr><td>- Olsza</td><td>".$row["olsza"]." kU/I</td><td>".$_SESSION['ol']."</td></tr>";
                                                            
                                                        echo "<tr><td>- Brzoza</td><td>".$row["brzoza"]." kU/I</td><td>".$_SESSION['br']."</td></tr>";
                                                           
                                                        echo "<tr><td>- Topola</td><td>".$row["topola"]." kU/I</td><td>".$_SESSION['to']."</td></tr>";
                                                           
                                                        echo "<tr><td>- Dąb</td><td>".$row["dab"]." kU/I</td><td>".$_SESSION['da']."</td></tr>";
                                                            
                                                        echo "<tr><td>- Trawy</td><td>".$row["trawy"]." kU/I</td><td>".$_SESSION['tr']."</td></tr>";
                                                            
                                                        echo "<tr><td>- Babka lancetowata</td><td>".$row["babka_lancetowata"]." kU/I</td><td>".$_SESSION['bab']."</td></tr>";
                                                         
                                                        echo "<tr><td>- Szczaw</td><td>".$row["szczaw"]." kU/I</td><td>".$_SESSION['sz']."</td></tr>";
                                                         
                                                        echo "<tr><td>- Pokrzywa</td><td>".$row["pokrzywa"]." kU/I</td><td>".$_SESSION['pok']."</td></tr>";
                                                           
                                                        echo "<tr><td>- Komosa</td><td>".$row["komosa"]." kU/I</td><td>".$_SESSION['ko']."</td></tr>";
                                                          
                                                        echo "<tr><td>- Bylica</td><td>".$row["bylica"]." kU/I</td><td>".$_SESSION['by']."</td></tr>";
                                                           
                                                        echo "<tr><td>- Ambrozja</td><td>".$row["ambrozja"]." kU/I</td><td>".$_SESSION['am']."</td></tr>";
                                                        
                                                        echo "<tr><td>- Cladosporium</td><td>".$row["cladosporium"]." kU/I</td><td>".$_SESSION['cl']."</td></tr>";
                                                          
                                                        echo "<tr><td>- Alternaria</td><td>".$row["alternaria"]." kU/I</td><td>".$_SESSION['alt']."</td></tr></table>";    
                                                        
                                                        echo "<h4>Spostrzeżenia lekarza dotyczące tego badania</h4>";

                                                        echo "<p> - " . $row["analiza"]. "</p><br/>";

                                                    }?>
                                                        
                                                <br />
                                                <!--<h4>Objaśnienie klas</h4>-->
                                                     <div class="panel-body">
                                                        <div class="table-responsive table-bordered col-md-11">
                                                            <table class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Klasa specyficznego IgE</th>
                                                                        <th>kU/I</th>
                                                                        <th>Znaczenie</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>0</td>
                                                                        <td>0,35</td>
                                                                        <td>Wynik negatywny.</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>1</td>
                                                                        <td>0,35 - 0,7</td>
                                                                        <td>Wynik niski.</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>2</td>
                                                                        <td>0,7 - 3,5</td>
                                                                        <td>Wynik umiarkowany.</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>3</td>
                                                                        <td>3,5 - 17,5</td>
                                                                        <td>Wynik wysoki.</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>4</td>
                                                                        <td>17,5 - 50</td>
                                                                        <td>Wynik bardzo wysoki.</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>5</td>
                                                                        <td>50 - 100</td>
                                                                        <td>Wynik bardzo wysoki.</td>
                                                                    </tr>    
                                                                    <tr>
                                                                        <td>6</td>
                                                                        <td>100 i więcej</td>
                                                                        <td>Wynik bardzo wysoki.</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <label class="control-label col-md-12"></label>
                                                        <p class="col-md-12">Źródło: Wojciech Mędrala, Podstawy alergologii, Wrocław, 2006, Górnicki Wydawnictwo Medyczne, s. 197.</p>
                                                    </div>
                                                    <a href="add_ige.php" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">Dodaj kolejne wyniki badań</a>
                                                <?php 
                                                    }
                                                }
                                                else 
                                                {
                                                    echo "<p> - Brak danych.</p><br/>"; ?>
                                                    <a href="add_ige.php" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">Dodaj wyniki badań</a>
                                                <?php                                   
                                                }
                                                ?>
                                            </div>

                                            <div class="tab-pane fade" id="profile"> <!-- ANALIZA @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->
                                                <?php
                                                $sql = "SELECT data, leszczyna, olsza, brzoza, topola, dab, trawy, babka_lancetowata, szczaw, pokrzywa, komosa, bylica, ambrozja, cladosporium, alternaria FROM ige, users WHERE ID_user=ID AND imie='".$_SESSION['imieview']."' AND nazwisko='".$_SESSION['nazwiskoview']."' ORDER BY data DESC LIMIT 1";
                                                $result = $conn->query($sql);
                                                if ($result->num_rows > 0) 
                                                {
                                                    while($row = $result->fetch_assoc()) 
                                                    {
                                                        echo "<br/><h3>Komputerowa analiza ostatnich wykonanych wyników badań z dnia: <mark>".$row["data"]."</mark></h3>";
                                                        echo "<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laboriosam illum itaque est tempore sit deleniti, magni harum vel maxime, possimus natus tempora aliquam doloremque tenetur quos eaque. Dolorem, dolorum, saepe!</p>";

                                                        ?>
                                                        <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-md-4 ">
                                                                <div class="alert alert-danger text-center">
                                                                    <h4>Wysokie stężenie alergenu</h4> 
                                                                    <hr />
                                                                    <h4>Bardzo wysokie prawdopodobieństwo wystąpienia objawów uczulenia</h4> 
                                                                    <hr />
                                                                    <h4>Alergeny:</h4> 
                                                                    <?php 
                                                                    if($row["leszczyna"]>=17.5){echo "<p>Leszczyna</p>"; }
                                                                    if($row["olsza"]>=17.5){echo "<p>Olsza</p>"; }
                                                                    if($row["brzoza"]>=17.5){echo "<p>Brzoza</p>"; }
                                                                    if($row["topola"]>=17.5){echo "<p>Topola</p>"; }
                                                                    if($row["dab"]>=17.5){echo "<p>Dab</p>"; }
                                                                    if($row["trawy"]>=17.5){echo "<p>Trawy</p>"; }
                                                                    if($row["babka_lancetowata"]>=17.5){echo "<p>Babka Lancetowata</p>"; }
                                                                    if($row["szczaw"]>=17.5){echo "<p>Szczaw</p>"; }
                                                                    if($row["pokrzywa"]>=17.5){echo "<p>Pokrzywa</p>"; }
                                                                    if($row["komosa"]>=17.5){echo "<p>Komosa</p>"; }
                                                                    if($row["bylica"]>=17.5){echo "<p>Bylica</p>"; }
                                                                    if($row["ambrozja"]>=17.5){echo "<p>Ambrozja</p>"; }
                                                                    if($row["cladosporium"]>=17.5){echo "<p>Cladosporium</p>"; }
                                                                    if($row["alternaria"]>=17.5){echo "<p>Alternaria</p>"; }
                                                                    ?>
                                                                </div>
                                                            </div>
             
                                                            <div class="col-md-4 ">
                                                                <div class="alert alert-info text-center">
                                                                    <h4>Umiarkowane stężenie alergenu</h4> 
                                                                    <hr />
                                                                    <h4>Niskie prawdopodobieństwo wystąpienia objawów uczulenia</h4> 
                                                                    <hr />
                                                                    <h4>Alergeny:</h4>
                                                                    <?php 
                                                                    if($row["leszczyna"]>0.35 && $row["leszczyna"]<17.5){echo "<p>Leszczyna</p>"; }
                                                                    if($row["olsza"]>0.35 && $row["olsza"]<17.5){echo "<p>Olsza</p>"; }
                                                                    if($row["brzoza"]>0.35 && $row["brzoza"]<17.5){echo "<p>Brzoza</p>"; }
                                                                    if($row["topola"]>0.35 && $row["topola"]<17.5){echo "<p>Topola</p>"; }
                                                                    if($row["dab"]>0.35 && $row["dab"]<17.5){echo "<p>Dab</p>"; }
                                                                    if($row["trawy"]>0.35 && $row["trawy"]<17.5){echo "<p>Trawy</p>"; }
                                                                    if($row["babka_lancetowata"]>0.35 && $row["babka_lancetowata"]<17.5){echo "<p>Babka Lancetowata</p>"; }
                                                                    if($row["szczaw"]>0.35 && $row["szczaw"]<17.5){echo "<p>Szczaw</p>"; }
                                                                    if($row["pokrzywa"]>0.35 && $row["pokrzywa"]<17.5){echo "<p>Pokrzywa</p>"; }
                                                                    if($row["komosa"]>0.35 && $row["komosa"]<17.5){echo "<p>Komosa</p>"; }
                                                                    if($row["bylica"]>0.35 && $row["bylica"]<17.5){echo "<p>Bylica</p>"; }
                                                                    if($row["ambrozja"]>0.35 && $row["ambrozja"]<17.5){echo "<p>Ambrozja</p>"; }
                                                                    if($row["cladosporium"]>0.35 && $row["cladosporium"]<17.5){echo "<p>Cladosporium</p>"; }
                                                                    if($row["alternaria"]>0.35 && $row["alternaria"]<17.5){echo "<p>Alternaria</p>"; }
                                                                    ?> 
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 ">
                                                                <div class="alert alert-success text-center">
                                                                    <h4>Brak stężenia alergenu</h4> <!-- brak alergi do 0,35 -->
                                                                    <hr />
                                                                    <h4>Wynik negatywny. Brak uczulenia</h4> 
                                                                    <hr />
                                                                    <h4>Alergeny:</h4>
                                                                    <?php 
                                                                    if($row["leszczyna"]<=0.35){echo "<p>Leszczyna</p>"; }
                                                                    if($row["olsza"]<=0.35){echo "<p>Olsza</p>"; }
                                                                    if($row["brzoza"]<=0.35){echo "<p>Brzoza</p>"; }
                                                                    if($row["topola"]<=0.35){echo "<p>Topola</p>"; }
                                                                    if($row["dab"]<=0.35){echo "<p>Dab</p>"; }
                                                                    if($row["trawy"]<=0.35){echo "<p>Trawy</p>"; }
                                                                    if($row["babka_lancetowata"]<=0.35){echo "<p>Babka Lancetowata</p>"; }
                                                                    if($row["szczaw"]<=0.35){echo "<p>Szczaw</p>"; }
                                                                    if($row["pokrzywa"]<=0.35){echo "<p>Pokrzywa</p>"; }
                                                                    if($row["komosa"]<=0.35){echo "<p>Komosa</p>"; }
                                                                    if($row["bylica"]<=0.35){echo "<p>Bylica</p>"; }
                                                                    if($row["ambrozja"]<=0.35){echo "<p>Ambrozja</p>"; }
                                                                    if($row["cladosporium"]<=0.35){echo "<p>Cladosporium</p>"; }
                                                                    if($row["alternaria"]<=0.35){echo "<p>Alternaria</p>"; }
                                                                    ?> 
                                                                </div>
                                                            </div>
                                                        </div>
                                                        </div>
                                                        <?php
                                                    }
                                                } else 
                                                {
                                                    echo "<p> - Brak analizy.</p><br/>";                                 
                                                }
                                                ?>
                                          
                                                
                                            </div>

                                            <div class="tab-pane fade" id="messages">  <!-- WNIOSKI LEKARZA @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->
                                                <h3>Wnioski lekarza</h3>
                                                <?php
                                                $conn = new mysqli($host, $db_user, $db_password, $db_name);
                                                if ($conn->connect_error) 
                                                {
                                                    printf("Connection failed: " . $conn->connect_error);
                                                    exit();
                                                }
                                                $sql = "SELECT karta FROM users WHERE imie='".$_SESSION['imieview']."' AND nazwisko='".$_SESSION['nazwiskoview']."' ";
                                                $result = $conn->query($sql);
                                                if ($result->num_rows > 0) 
                                                {
                                                    while($row = $result->fetch_assoc()) 
                                                    {
                                                        echo "<p>".$row["karta"]."</p><br/>";
                                                    }
                                                } 
                                                else 
                                                {
                                                    echo "<p> - Brak danych.</p><br/>";
                                                }
                                                ?>
                                                <label class="control-label col-md-12"></label>
                                                <label class="control-label col-md-12"></label>
                                                <label class="control-label col-md-12"></label>
                                                <label class="control-label col-md-12"></label>
                                                <form action="<?php ?>" method="post">
                                                    <div class="form-group">
                                                        <label for="example">Uzupełnij diagnozę o dodatkowe wnioski:</label>
                                                        <input type="text" name="data" class="form-control" id="data" placeholder="Bieżąca data" />
                                                        <label class="control-label col-md-12"></label>
                                                        <textarea name="example" class="form-control" rows="4" placeholder="Wpis do karty"></textarea>
                                                        <div class="form-action">
                                                            <label class="control-label col-md-12"></label>
                                                            <input type="submit" class="btn btn-form control-label col-md-2" name="submit" value="Uzupełnij diagnozę">
                                                            <label class="control-label col-md-12"></label>
                                                            <?php 
                                                            if(isset($_SESSION['good']))
                                                            {
                                                                echo'<div class="alert">'.$_SESSION['good'].'</div>';
                                                                unset($_SESSION['good']);
                                                            }?>
                                                            <?php
                                                            if(isset($_SESSION['error1']))
                                                            {
                                                                echo'<div class="error">'.$_SESSION['error1'].'</div>';
                                                                unset($_SESSION['error1']); 
                                                            }?>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="tab-pane fade" id="other"> <!-- STATYSTYKI @@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->
                                                <h3>Statystyki wyników badań wszystkich pacjentów naszej poradni</h3>
                                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laboriosam illum itaque est tempore sit deleniti, magni harum vel maxime, possimus natus tempora aliquam doloremque tenetur quos eaque. Dolorem, dolorum, saepe!</p>
                                                <?php
                                                $sql = "SELECT ROUND(AVG(leszczyna),2) AS stat, ROUND(AVG(olsza),2) AS stat2, ROUND(AVG(brzoza),2) AS stat3, ROUND(AVG(topola),2) AS stat4, ROUND(AVG(dab),2) AS stat5, ROUND(AVG(trawy),2) AS stat6, ROUND(AVG(babka_lancetowata),2) AS stat7, ROUND(AVG(szczaw),2) AS stat8, ROUND(AVG(pokrzywa),2) AS stat9, ROUND(AVG(komosa),2) AS stat10, ROUND(AVG(bylica),2) AS stat11, ROUND(AVG(ambrozja),2) AS stat12, ROUND(AVG(cladosporium),2) AS stat13, ROUND(AVG(alternaria),2) AS stat14 FROM ige ";
                                                $result = $conn->query($sql);
                                                if ($result->num_rows > 0) 
                                                {
                                                    while($row = $result->fetch_assoc()) 
                                                    {?>
                                                       <label class="col-md-3"><h4>Leszczyna - <?php echo "".$row["stat"]." kU/I"; ?></h4></label>
                                                       <label class="col-md-7">
                                                        <div class="panel-body">
                                                            <div class="progress">
                                                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo "".$row["stat"]."%"; ?>;"></div>
                                                            </div>
                                                        </div></label>
                                                          
                                                          <label class="col-md-3"><h4>Olsza - <?php echo "".$row["stat2"]." kU/I"; ?></h4></label>
                                                       <label class="col-md-7">
                                                        <div class="panel-body">
                                                            <div class="progress">
                                                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo "".$row["stat2"]."%"; ?>;"></div>
                                                            </div>
                                                        </div></label>
                                                        
                                                        <label class="col-md-3"><h4>Brzoza - <?php echo "".$row["stat3"]." kU/I"; ?></h4></label>
                                                       <label class="col-md-7">
                                                        <div class="panel-body">
                                                            <div class="progress">
                                                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo "".$row["stat3"]."%"; ?>;"></div>
                                                            </div>
                                                        </div></label>
                                                                                                                
                                                        <label class="col-md-3"><h4>Topola - <?php echo "".$row["stat4"]." kU/I"; ?></h4></label>
                                                       <label class="col-md-7">
                                                        <div class="panel-body">
                                                            <div class="progress">
                                                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo "".$row["stat4"]."%"; ?>;"></div>
                                                            </div>
                                                        </div></label>   
                                                                                                             
                                                        <label class="col-md-3"><h4>Dąb - <?php echo "".$row["stat5"]." kU/I"; ?></h4></label>
                                                       <label class="col-md-7">
                                                        <div class="panel-body">
                                                            <div class="progress">
                                                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo "".$row["stat5"]."%"; ?>;"></div>
                                                            </div>
                                                        </div></label>
                                                                                                                
                                                        <label class="col-md-3"><h4>Trawy - <?php echo "".$row["stat6"]." kU/I"; ?></h4></label>
                                                       <label class="col-md-7">
                                                        <div class="panel-body">
                                                            <div class="progress">
                                                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo "".$row["stat6"]."%"; ?>;"></div>
                                                            </div>
                                                        </div></label>
                                                                                                                
                                                        <label class="col-md-3"><h4>Babka lancetowata - <?php echo "".$row["stat7"]." kU/I"; ?></h4></label>
                                                       <label class="col-md-7">
                                                        <div class="panel-body">
                                                            <div class="progress">
                                                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo "".$row["stat7"]."%"; ?>;"></div>
                                                            </div>
                                                        </div></label>
                                                                                                                
                                                        <label class="col-md-3"><h4>Szczaw - <?php echo "".$row["stat8"]." kU/I"; ?></h4></label>
                                                       <label class="col-md-7">
                                                        <div class="panel-body">
                                                            <div class="progress">
                                                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo "".$row["stat8"]."%"; ?>;"></div>
                                                            </div>
                                                        </div></label>
                                                                                                                
                                                        <label class="col-md-3"><h4>Pokrzywa - <?php echo "".$row["stat9"]." kU/I"; ?></h4></label>
                                                       <label class="col-md-7">
                                                        <div class="panel-body">
                                                            <div class="progress">
                                                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo "".$row["stat9"]."%"; ?>;"></div>
                                                            </div>
                                                        </div></label>
                                                                                                                
                                                        <label class="col-md-3"><h4>Komosa - <?php echo "".$row["stat10"]." kU/I"; ?></h4></label>
                                                       <label class="col-md-7">
                                                        <div class="panel-body">
                                                            <div class="progress">
                                                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo "".$row["stat10"]."%"; ?>;"></div>
                                                            </div>
                                                        </div></label>
                                                                                                                
                                                        <label class="col-md-3"><h4>Bylica - <?php echo "".$row["stat11"]." kU/I"; ?></h4></label>
                                                       <label class="col-md-7">
                                                        <div class="panel-body">
                                                            <div class="progress">
                                                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo "".$row["stat11"]."%"; ?>;"></div>
                                                            </div>
                                                        </div></label>
                                                                                                                
                                                        <label class="col-md-3"><h4>Ambrozja - <?php echo "".$row["stat12"]." kU/I"; ?></h4></label>
                                                       <label class="col-md-7">
                                                        <div class="panel-body">
                                                            <div class="progress">
                                                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo "".$row["stat12"]."%"; ?>;"></div>
                                                            </div>
                                                        </div></label>
                                                                                                                
                                                        <label class="col-md-3"><h4>Cladosporium - <?php echo "".$row["stat13"]." kU/I"; ?></h4></label>
                                                       <label class="col-md-7">
                                                        <div class="panel-body">
                                                            <div class="progress">
                                                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo "".$row["stat13"]."%"; ?>;"></div>
                                                            </div>
                                                        </div></label>
                                                                                                                
                                                        <label class="col-md-3"><h4>Alternaria - <?php echo "".$row["stat14"]." kU/I"; ?></h4></label>
                                                       <label class="col-md-7">
                                                        <div class="panel-body">
                                                            <div class="progress">
                                                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo "".$row["stat14"]."%"; ?>;"></div>
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
