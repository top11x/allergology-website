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
                                        </ul>
                                        <!-- Tab panes -->
                                        <div class="tab-content">
                                            <div class="tab-pane fade in active" id="home">
                                                <h4>Wyniki badania</h4>
                                                <?php
                                                require_once"connect.php";
                                                $conn = new mysqli($host, $db_user, $db_password, $db_name);
                                                if ($conn->connect_error) 
                                                {
                                                    printf("Connection failed: " . $conn->connect_error);
                                                    exit();
                                                }
                                                $sql = "SELECT leszczyna, olsza, brzoza, topola, dab, trawy, babka_lancetowata, szczaw, pokrzywa, komosa, bylica, ambrozja, cladosporium, alternaria FROM ige, users WHERE ID_user=ID AND imie='".$_SESSION['imieview']."' AND nazwisko='".$_SESSION['nazwiskoview']."' ";
                                                $result = $conn->query($sql);
                                                if ($result->num_rows > 0) 
                                                {
                                                    while($row = $result->fetch_assoc()) 
                                                    {
                                                        echo "<table><tr><th>Alergeny</th><th>Stężenia &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th><th>Klasa</th></tr>"; 
                                                        echo "<tr><td>- Leszczyna</td><td>".$row["leszczyna"]." kU/I</td><td>".$_SESSION["le"]."</td></tr>";
                                                            if($row["leszczyna"]<0.3){
                                                                $_SESSION['le']='0';}
                                                            if($row["leszczyna"]>=0.3 && $row["leszczyna"]<=0.7){
                                                                $_SESSION['le']='1';}
                                                            if($row["leszczyna"]>0.7 && $row["leszczyna"]<=3.5){
                                                                $_SESSION['le']='2';}
                                                            if($row["leszczyna"]>3.5 && $row["leszczyna"]<=17.5){
                                                                $_SESSION['le']='3';}
                                                            if($row["leszczyna"]>17.5 && $row["leszczyna"]<=50){
                                                                $_SESSION['le']='4';}
                                                            if($row["leszczyna"]>50){
                                                                $_SESSION['le']='5';}
                                                        echo "<tr><td>- Olsza</td><td>".$row["olsza"]." kU/I</td><td>".$_SESSION['ol']."</td></tr>";
                                                            if($row["olsza"]<0.3){
                                                                $_SESSION['ol']='0';}
                                                            if($row["olsza"]>=0.3 && $row["olsza"]<=0.7){
                                                                $_SESSION['ol']='1';}
                                                            if($row["olsza"]>0.7 && $row["olsza"]<=3.5){
                                                                $_SESSION['ol']='2';}
                                                            if($row["olsza"]>3.5 && $row["olsza"]<=17.5){
                                                                $_SESSION['ol']='3';}
                                                            if($row["olsza"]>17.5 && $row["olsza"]<=50){
                                                                $_SESSION['ol']='4';}
                                                            if($row["olsza"]>50){
                                                                $_SESSION['ol']='5';} 
                                                        echo "<tr><td>- Brzoza</td><td>".$row["brzoza"]." kU/I</td><td>".$_SESSION['br']."</td></tr>";
                                                            if($row["brzoza"]<0.3){
                                                                $_SESSION['br']='0';}
                                                            if($row["brzoza"]>=0.3 && $row["brzoza"]<=0.7){
                                                                $_SESSION['br']='1';}
                                                            if($row["brzoza"]>0.7 && $row["brzoza"]<=3.5){
                                                                $_SESSION['br']='2';}
                                                            if($row["brzoza"]>3.5 && $row["brzoza"]<=17.5){
                                                                $_SESSION['br']='3';}
                                                            if($row["brzoza"]>17.5 && $row["brzoza"]<=50){
                                                                $_SESSION['br']='4';}
                                                            if($row["brzoza"]>50){
                                                                $_SESSION['br']='5';} 
                                                        echo "<tr><td>- Topola</td><td>".$row["topola"]." kU/I</td><td>".$_SESSION['to']."</td></tr>";
                                                            if($row["topola"]<0.3){
                                                                $_SESSION['to']='0';}
                                                            if($row["topola"]>=0.3 && $row["topola"]<=0.7){
                                                                $_SESSION['to']='1';}
                                                            if($row["topola"]>0.7 && $row["topola"]<=3.5){
                                                                $_SESSION['to']='2';}
                                                            if($row["topola"]>3.5 && $row["topola"]<=17.5){
                                                                $_SESSION['to']='3';}
                                                            if($row["topola"]>17.5 && $row["topola"]<=50){
                                                                $_SESSION['to']='4';}
                                                            if($row["topola"]>50){
                                                                $_SESSION['to']='5';} 
                                                        echo "<tr><td>- Dąb</td><td>".$row["dab"]." kU/I</td><td>".$_SESSION['da']."</td></tr>";
                                                            if($row["dab"]<0.3){
                                                                $_SESSION['da']='0';}
                                                            if($row["dab"]>=0.3 && $row["dab"]<=0.7){
                                                                $_SESSION['da']='1';}
                                                            if($row["dab"]>0.7 && $row["dab"]<=3.5){
                                                                $_SESSION['da']='2';}
                                                            if($row["dab"]>3.5 && $row["dab"]<=17.5){
                                                                $_SESSION['da']='3';}
                                                            if($row["dab"]>17.5 && $row["dab"]<=50){
                                                                $_SESSION['da']='4';}
                                                            if($row["dab"]>50){
                                                                $_SESSION['da']='5';} 
                                                        echo "<tr><td>- Trawy</td><td>".$row["trawy"]." kU/I</td><td>".$_SESSION['tr']."</td></tr>";
                                                            if($row["trawy"]<0.3){
                                                                $_SESSION['tr']='0';}
                                                            if($row["trawy"]>=0.3 && $row["trawy"]<=0.7){
                                                                $_SESSION['tr']='1';}
                                                            if($row["trawy"]>0.7 && $row["trawy"]<=3.5){
                                                                $_SESSION['tr']='2';}
                                                            if($row["trawy"]>3.5 && $row["trawy"]<=17.5){
                                                                $_SESSION['tr']='3';}
                                                            if($row["trawy"]>17.5 && $row["trawy"]<=50){
                                                                $_SESSION['tr']='4';}
                                                            if($row["trawy"]>50){
                                                                $_SESSION['tr']='5';} 
                                                        echo "<tr><td>- Babka lancetowata</td><td>".$row["babka_lancetowata"]." kU/I</td><td>".$_SESSION['bab']."</td></tr>";
                                                            if($row["babka_lancetowata"]<0.3){
                                                                $_SESSION['bab']='0';}
                                                            if($row["babka_lancetowata"]>=0.3 && $row["babka_lancetowata"]<=0.7){
                                                                $_SESSION['bab']='1';}
                                                            if($row["babka_lancetowata"]>0.7 && $row["babka_lancetowata"]<=3.5){
                                                                $_SESSION['bab']='2';}
                                                            if($row["babka_lancetowata"]>3.5 && $row["babka_lancetowata"]<=17.5){
                                                                $_SESSION['bab']='3';}
                                                            if($row["babka_lancetowata"]>17.5 && $row["babka_lancetowata"]<=50){
                                                                $_SESSION['bab']='4';}
                                                            if($row["babka_lancetowata"]>50){
                                                                $_SESSION['bab']='5';} 
                                                        echo "<tr><td>- Szczaw</td><td>".$row["szczaw"]." kU/I</td><td>".$_SESSION['sz']."</td></tr>";
                                                            if($row["szczaw"]<0.3){
                                                                $_SESSION['sz']='0';}
                                                            if($row["szczaw"]>=0.3 && $row["szczaw"]<=0.7){
                                                                $_SESSION['sz']='1';}
                                                            if($row["szczaw"]>0.7 && $row["szczaw"]<=3.5){
                                                                $_SESSION['sz']='2';}
                                                            if($row["szczaw"]>3.5 && $row["szczaw"]<=17.5){
                                                                $_SESSION['sz']='3';}
                                                            if($row["szczaw"]>17.5 && $row["szczaw"]<=50){
                                                                $_SESSION['sz']='4';}
                                                            if($row["szczaw"]>50){
                                                                $_SESSION['sz']='5';} 
                                                        echo "<tr><td>- Pokrzywa</td><td>".$row["pokrzywa"]." kU/I</td><td>".$_SESSION['pok']."</td></tr>";
                                                            if($row["pokrzywa"]<0.3){
                                                                $_SESSION['pok']='0';}
                                                            if($row["pokrzywa"]>=0.3 && $row["pokrzywa"]<=0.7){
                                                                $_SESSION['pok']='1';}
                                                            if($row["pokrzywa"]>0.7 && $row["pokrzywa"]<=3.5){
                                                                $_SESSION['pok']='2';}
                                                            if($row["pokrzywa"]>3.5 && $row["pokrzywa"]<=17.5){
                                                                $_SESSION['pok']='3';}
                                                            if($row["pokrzywa"]>17.5 && $row["pokrzywa"]<=50){
                                                                $_SESSION['pok']='4';}
                                                            if($row["pokrzywa"]>50){
                                                                $_SESSION['pok']='5';} 
                                                        echo "<tr><td>- Komosa</td><td>".$row["komosa"]." kU/I</td><td>".$_SESSION['ko']."</td></tr>";
                                                            if($row["komosa"]<0.3){
                                                                $_SESSION['ko']='0';}
                                                            if($row["komosa"]>=0.3 && $row["komosa"]<=0.7){
                                                                $_SESSION['ko']='1';}
                                                            if($row["komosa"]>0.7 && $row["komosa"]<=3.5){
                                                                $_SESSION['ko']='2';}
                                                            if($row["komosa"]>3.5 && $row["komosa"]<=17.5){
                                                                $_SESSION['ko']='3';}
                                                            if($row["komosa"]>17.5 && $row["komosa"]<=50){
                                                                $_SESSION['ko']='4';}
                                                            if($row["komosa"]>50){
                                                                $_SESSION['ko']='5';} 
                                                        echo "<tr><td>- Bylica</td><td>".$row["bylica"]." kU/I</td><td>".$_SESSION['by']."</td></tr>";
                                                            if($row["bylica"]<0.3){
                                                                $_SESSION['by']='0';}
                                                            if($row["bylica"]>=0.3 && $row["bylica"]<=0.7){
                                                                $_SESSION['by']='1';}
                                                            if($row["bylica"]>0.7 && $row["bylica"]<=3.5){
                                                                $_SESSION['by']='2';}
                                                            if($row["bylica"]>3.5 && $row["bylica"]<=17.5){
                                                                $_SESSION['by']='3';}
                                                            if($row["bylica"]>17.5 && $row["bylica"]<=50){
                                                                $_SESSION['by']='4';}
                                                            if($row["bylica"]>50){
                                                                $_SESSION['by']='5';} 
                                                        echo "<tr><td>- Ambrozja</td><td>".$row["ambrozja"]." kU/I</td><td>".$_SESSION['am']."</td></tr>";
                                                            if($row["ambrozja"]<0.3){
                                                                $_SESSION['am']='0';}
                                                            if($row["ambrozja"]>=0.3 && $row["ambrozja"]<=0.7){
                                                                $_SESSION['am']='1';}
                                                            if($row["ambrozja"]>0.7 && $row["ambrozja"]<=3.5){
                                                                $_SESSION['am']='2';}
                                                            if($row["ambrozja"]>3.5 && $row["ambrozja"]<=17.5){
                                                                $_SESSION['am']='3';}
                                                            if($row["ambrozja"]>17.5 && $row["ambrozja"]<=50){
                                                                $_SESSION['am']='4';}
                                                            if($row["ambrozja"]>50){
                                                                $_SESSION['am']='5';} 
                                                        echo "<tr><td>- Cladosporium</td><td>".$row["cladosporium"]." kU/I</td><td>".$_SESSION['cl']."</td></tr>";
                                                            if($row["cladosporium"]<0.3){
                                                                $_SESSION['cl']='0';}
                                                            if($row["cladosporium"]>=0.3 && $row["cladosporium"]<=0.7){
                                                                $_SESSION['cl']='1';}
                                                            if($row["cladosporium"]>0.7 && $row["cladosporium"]<=3.5){
                                                                $_SESSION['cl']='2';}
                                                            if($row["cladosporium"]>3.5 && $row["cladosporium"]<=17.5){
                                                                $_SESSION['cl']='3';}
                                                            if($row["cladosporium"]>17.5 && $row["cladosporium"]<=50){
                                                                $_SESSION['cl']='4';}
                                                            if($row["cladosporium"]>50){
                                                                $_SESSION['cl']='5';} 
                                                        echo "<tr><td>- Alternaria</td><td>".$row["alternaria"]." kU/I</td><td>".$_SESSION['alt']."</td></tr></table>";
                                                            if($row["alternaria"]<0.3){
                                                                $_SESSION['alt']='0';}
                                                            if($row["alternaria"]>=0.3 && $row["alternaria"]<=0.7){
                                                                $_SESSION['alt']='1';}
                                                            if($row["alternaria"]>0.7 && $row["alternaria"]<=3.5){
                                                                $_SESSION['alt']='2';}
                                                            if($row["alternaria"]>3.5 && $row["alternaria"]<=17.5){
                                                                $_SESSION['alt']='3';}
                                                            if($row["alternaria"]>17.5 && $row["alternaria"]<=50){
                                                                $_SESSION['alt']='4';}
                                                            if($row["alternaria"]>50){
                                                                $_SESSION['alt']='5';}  ?>
                                                <br />
                                                <h4>Spostrzeżenia lekarza dotyczące badania</h4>
                                                <?php
                                                        $sql = "SELECT analiza FROM ige, users WHERE ID_user=ID AND imie='".$_SESSION['imieview']."' AND nazwisko='".$_SESSION['nazwiskoview']."' ";
                                                        $result = $conn->query($sql);
                                                        if ($result->num_rows > 0) 
                                                        {
                                                            while($row = $result->fetch_assoc()) 
                                                            {
                                                                echo "<p> - " . $row["analiza"]. "</p><br/>";
                                                            }
                                                        }
                                                    }
                                                } 
                                                else 
                                                {
                                                    echo "<p> - Brak danych.</p><br/>"; ?>
                                                <a href="add_ige.php" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">Dodaj wyniki badań</a>
                                                <?php                                   
                                                }
                                                ?>
                                                <h4>Objaśnienie klas</h4>
                                                <div class="panel-body">
                                                    <div class="table-responsive table-bordered">
                                                        <table class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th>Stężenie [kU/I]</th>
                                                                    <th>Klasa</th>
                                                                    <th>Objaśnienie</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>mniej niż 0,3</td>
                                                                    <td>0</td>
                                                                    <td>Nie udokumentowano specyficznych przeciwciał.</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>0,3 - 0,7</td>
                                                                    <td>1</td>
                                                                    <td>Bardzo niskie miano przeciwciał, często bez występujących objawów klinicznych.</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>0,7 - 3,5</td>
                                                                    <td>2</td>
                                                                    <td>Niskie miano przeciwciał, istniejące uczulenie, często z objawami klinicznymi.</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>3,5 - 17,5</td>
                                                                    <td>3</td>
                                                                    <td>Wykryto określone przeciwciała, często występują objawy kliniczne.</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>17,5 - 50</td>
                                                                    <td>4</td>
                                                                    <td>Silna reakcja przeciwciał, niemalzawsze ze spółistniejącymi objawami kliniczymi.</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>więcej niż 50</td>
                                                                    <td>5</td>
                                                                    <td>Bardzo wysokie miano przeciwciał.</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="tab-pane fade" id="profile">
                                                <h4>Analiza</h4>
                                                <p>Będzie działać kiedyś.</p>
                                                <?php
                                                $sql = "SELECT leszczyna FROM ige, users WHERE ID_user=ID AND imie='".$_SESSION['imieview']."' AND nazwisko='".$_SESSION['nazwiskoview']."' ";
                                                $result = $conn->query($sql);
                                                if ($result->num_rows > 0) 
                                                {
                                                    while($row = $result->fetch_assoc()) 
                                                    {
                                                        echo "<p> - Leszczyna: " . $row["leszczyna"]. " <br/></p>";
                                                        if($row["leszczyna"]>1)
                                                        {
                                                            echo "gut gut";
                                                        }
                                                    }
                                                } else 
                                                {
                                                    echo "<p> - Brak analizy.</p><br/>";                                 
                                                }
                                                ?>
                                            </div>

                                            <div class="tab-pane fade" id="messages">
                                                <h4>Wnioski lekarza</h4>
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
                                                        echo "<p> - " . $row["karta"]. "</p><br/>";
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
