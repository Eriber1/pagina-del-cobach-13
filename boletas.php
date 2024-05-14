<?php
require ('conexion/cn.php');
require('fpdf/fpdf.php');
$usuario=$_POST['usuario'];

$consulta = "SELECT * FROM calificaciones WHERE matricula = '$usuario'";
$consulta2 = "SELECT * FROM alumnos WHERE matricula = '$usuario'";

$resultado=mysqli_query($conexion,$consulta);
$filas=mysqli_num_rows($resultado);

$resultado2 = mysqli_query($conexion, $consulta2);
$filas2=mysqli_num_rows($resultado2);

date_default_timezone_set('America/Mexico_City');

if ($filas){
    class PDF extends FPDF
    {
    // Cabecera de página
    function Header()
    {
        
        // Movernos a la derecha
        $this->Cell(300);
        //imagen el la esquina superior izquierda
        $this->Image('img/logo.png',30,10,-300);
        $this->Image('img/logo.png',700,10,-300);
        // titulo
        $this->SetFont('Arial','B',8);
        $this->Cell(150,0,utf8_decode("2022, Año de Ricardo FLores Magón"),0,0,'C');
        $this->SetFont('Arial','B',20);
        $this->Cell(-150,50,'COLEGIO DE BACHILLERES ',0,0,'C');
        $this->SetFont('Arial','B',10);
        $this->Cell(100,100,'PLANTEL 13 TUXTLA  CLAVE: 07ECB00130 PERIODO: 2022 A',0,0,'C');
        $this->Cell(-50,130,'TUXTLA GUTIERREZ,  CHIAPAS A',0,0,'R');
        //
        $diassemana = array("DOMINGO","LUNES","MARTES","MIERCOLES","JUEVES","VIERNES","SABADO");
        $meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
        $fecha=$diassemana[date('w')]." ".date('d')." DE ".$meses[date('n')-1]. " DEL ".date('Y') ;
        //
        $this->Cell(200,130,$fecha,0,1,'R');
        $this->Cell(725,-98,'BOLETA DE CALIFICACIONES PARCIALES DEL SEMESTRE 2022 A',0,0,'C');
      
        $this->Ln(20);
        $this->SetFont('Arial','B',12);
        $this->Cell(50,20,'Clave',1,0,'C',0);
        $this->Cell(300,20,'Materia',1,0,'C',0);
        $this->Cell(80,20,'Parcial 1',1,0,'C',0);
        $this->Cell(80,20,'Parcial 2',1,0,'C',0);
        $this->Cell(80,20,'Parcial 3',1,0,'C',0);
        $this->Cell(80,20,'C Final',1,0,'C',0);
        $this->Cell(80,20,'Turno',1,1,'L',0);
    }
    
    // Pie de página
    function Footer()
    {
        // Posición: a 1,5 cm del final
        $this->SetY(-230);
        // Arial italic 8
        $this->SetFont('Arial','BIU',12);
        // Número de página
        $this->Cell(725,350,utf8_decode("ING. LUIS VICENTE GUILÉN PÉREZ"),0,0,'C');
        $this->SetFont('Arial','I',12);
        $this->Cell(-715,375,utf8_decode("DIRECTOR DEL PLANTEL"),0,0,'C');
    
        $this->Image('img/Firma.png',350,500,90);
        $this->Image('img/Cello.png',500,500,150);
    
        $this->Ln(20);
        $this->SetFont('Arial','I',10);
        $this->Cell(150,30,'Matricula',0,0,'L',0);
        $this->Cell(250,30,'Nombre',0,0,'L',0);
        $this->Cell(180,30,'CURP',0,0,'L',0);
        $this->Cell(50,30,'GRADO',0,0,'C',0);
        $this->Cell(50,30,'GRUPO',0,0,'C',0);
        $this->Image('img/pie.png',15,575,600);
    }
    }
    $pdf=new PDF('L','pt','Letter');
    $pdf->AliasNbPages();
    $pdf->AddPage();
     
    
    while ($row=$resultado->fetch_assoc()) {
        $pdf->SetFont('Arial','I',10);
        $pdf->Cell(50,20,$row['clavemate'],1,0,'L',0);
        $pdf->SetFont('Arial','I',7.5);
        $pdf->Cell(300,20,$row['materia'],1,0,'L',0);
        $pdf->SetFont('Arial','I',10);
        $pdf->Cell(80,20,$row['parcial_1'],1,0,'C',0);
        $pdf->Cell(80,20,$row['parcial_2'],1,0,'C',0);
        $pdf->Cell(80,20,$row['parcial_3'],1,0,'C',0);
        $pdf->Cell(80,20,$row['calificaci'],1,0,'c',0);
        $pdf->Cell(80,20,$row['turno'],1,1,'L',0);
      
    } 
        $pdf->Ln(30);
    
    while ($row2=$resultado2->fetch_assoc()) {
        $pdf->SetFont('Arial','I',11);
        $pdf->Cell(150,30,$row2['matricula'],0,0,'L',0);
        $pdf->Cell(250,30,utf8_decode($row2['nombres']." ".$row2['papellido']." ".$row2['sapellido']),0,0,'L',0);
        $pdf->Cell(180,30,$row2['curp'],0,0,'L',0);
        $pdf->Cell(50,30,$row2['grado'],0,0,'C',0);
        $pdf->Cell(50,30,$row2['grupo'],0,0,'C',0);
    }

    
        $pdf->Output();
        

}
else{
    ?>
    <?php
    include("login.html");
    ?>
    <h1><center>ERROR DE AUTENTICACION</center> </h1>
    <?php
}

mysqli_free_result($resultado);
mysqli_close($conexion);