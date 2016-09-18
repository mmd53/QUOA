<!DOCTYPE html>
<html>

<head>
    <title> QuaOL </title>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="style.css">
</head>


<body>
<div class="container">
    
<h1>AUB</h1>


<?php

$input['CO2']= 430;
$input['Temp'] = 0;
$input['CO'] = 0;
$input['Noise'] = 10;
$input['Wind'] = 0;
$input['Press']= 0;
$input['UV'] = 0;
$input['Humid'] = 0;

?>

<ul>
    <li>
        CO2: <?php echo $input['CO2']; ?> 
    </li>

    <li>
        Temp: <?php echo $input['Temp']; ?> 
    </li>

    <li>
        CO: <?php echo $input['CO']; ?> 
    </li>

    <li>
        Noise: <?php echo $input['Noise']; ?> 
    </li>
    
    <li>
        Wind: <?php echo $input['Wind']; ?> 
    </li>

    <li>
        Pressure: <?php echo $input['Press']; ?> 
    </li>
    
    <li>
        UV: <?php echo $input['UV']; ?> 
    </li>

    <li>
        Humidity: <?php echo $input['Humid']; ?> 
    </li>
</ul>

<?php

//$want = array('CO2','Temp','CO','Noise','Wind','Press','Visib','Humid');
$want = array('CO2','Temp','CO','Noise','Wind','Press','UV','Humid');

foreach($want as $key=>$t)
{
    if ($input[$t] === 0 || $input[$t] === NULL){
        unset($want[$key]);
    }
}

//var_dump($want);
//function ranges
//Takes as input the value we have of some parameter
//As well as the smallest value for each range
//We classify the value in some range, and give it a percentage accordingly
//Ranges are classified by decreasing order

//Note: first value should be 0 (or minimum value)
function ranges($val,$arr) {
    foreach(  $arr as $ind => $grd ) {
        if($val < $ind){
            return $result;
        }
         //leave once we found the ideal range
        else{
            $result = $grd; //Assign the grade to the result
        }
    }
    
    return $result;
}

//function error
//Takes the value and the standard condition
//Calculates the error and returns a percentage of quality of life accordingly
//Can take into acc if we need the value to only bigger/smaller
//param = 0- two sided error
//param = 1- only bigger error
//param = -1 - only smaller error
function error($val,$stdval, $param)
{
    $go = ($param == 1 && $val > $stdval) || ($param == -1 && $val < $stdval);
    if ($param == 0 || $go) { 
       $perc = abs($val - $stdval) / $stdval;
    }
   
   else
   $perc = 0;
   
   return 1 - $perc;
}

//Use range function
$ra = array('CO2', 'CO','Noise','UV');

//Use error function
$err['Wind'] = 1;
$err['Temp']= 0;
$err['Press']= 1;
$err['Humid'] = 0;

//array of ranges to use for range function
$range['CO2'] = array(0=>90, 350=>80,1000=>60,2000=>30,5000=>15,40000=>0);
$range['CO'] = array (0=>90,9=>80,35=>50,900=>0);
$range['Noise'] = array(0=>100,60=>80,80=>60,95=>50,105=>40,110=>30,130=>0);
$range['UV'] = array(0=>100,2=>80,3=>65,5=>50,6=>40,7=>25,8=>15,10=>10,11=>5);


$stdval['Wind'] = 300;
$stdval['Temp']= 25;
$stdval['Press']= 25;
$stdval['Humid'] = 40;

//Calculate the percentage of the field
//first for those in $ra
//then for those in $err
$val = $input;

foreach ($ra as $temp)
{
    $val[$temp] = ranges($input[$temp],$range[$temp]); //Calculate the range of each parameter that
    //needs it   
}

foreach ($err as $temp=>$tat)
{
    $val[$temp] = error($input[$temp],$stdval[$temp], $tat)*100; //Calculate error according to stdval
}

//Coefficient of values
//0 or 1/N
//N the number of yes we want

$N = sizeOf($want);

foreach($val as $tat=>$temp)
{
    if(in_array($tat,$want)){
        $coef[$tat] = 1/$N; 
    } else{
        $coef[$tat] = 0;
    }

}

//$coef['CO2'] = 1/3;
//$coef['Temp'] = 1/3;
//$coef['CO'] = 1/3;
//$coef['Noise'] = 0;
//$coef['Wind'] = 0;
//$coef['Press']= 0;
//$coef['Visib'] = 0;
//$coef['Humid'] = 0;


$avg = 0;   

//Calculate the weighted average
foreach(  $val as $key => $value ){    
    $avg = $avg + $coef[$key]*$value; 
}

?>

<h1>Rating for AUB: <?php echo $avg; ?>% favourability rating. </h1>
 
</div>
</body>
</html>