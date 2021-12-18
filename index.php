<?php
// $question = 'این یک پرسش نمونه است';
// $msg = 'این یک پاسخ نمونه است';
// $en_name = 'hafez';
// $fa_name = 'حافظ';
$flag = 1;
error_reporting(0);

function N_len($x)
{
    $i = 0;
    
    while ($x > 1){
        $x /= 10;
        // echo "----$$" . $i . "   " . $x . "$$++++";
        $i++;
    }

    return $i;
}


$names_list = file_get_contents("people.json");
$names = json_decode($names_list, true);
$name_keys = array_keys($names);

$answers = array();
$q_text = fopen("messages.txt", "r");
$i = 0;
while (!feof($q_text)){
    $answers[$i] = fgets($q_text);
    $i++;
}


if ($_POST["person"]){
    $en_name = $_POST["person"];
    $fa_name = $names[$en_name];

    $question = $_POST["question"];

    if(empty($question)){
        $msg = "لطفا سوال خود را بپرس!";
        $en_name = array_rand($names,1);
        $fa_name = $names[$en_name];
        $flag = 0;
    }
    else{
        if (substr($question, -2) == "؟"){
        $random_n = hash("md5", $question.$en_name);
        $len = N_len(hexdec($random_n));
        $line = (hexdec($random_n) / pow(10 , $len - 3)) % 16;
        $msg = $answers[$line];
        $flag = 1;
        }
        else{
            $msg = "سوال درستی پرسیده نشده";
        }
    }
}
else{
    $en_name = array_rand($names,1);
    $fa_name = $names[$en_name];

    $msg = "لطفا سوال خود را بپرس!";
    $question = '';
    $flag = 0;

}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="styles/default.css">
    <title>مشاوره بزرگان</title>
</head>
<body>
<p id="copyright">تهیه شده برای درس کارگاه کامپیوتر،دانشکده کامییوتر، دانشگاه صنعتی شریف</p>
<div id="wrapper">
    <?php 
        if($flag){
            echo'
            <div id="title">
                <span id="label">پرسش:</span>
                <span id="question">';
        }
    ?>
    <?php if ($flag) {echo $question;} ?>
    <?php 
        if ($flag){
            echo '</span>
                </div>';
        }
            
    ?>
    <div id="container">
        <div id="message">
            <p><?php echo $msg ?></p>
        </div>
        <div id="person">
            <div id="person">
                <img src="images/people/<?php echo "$en_name.jpg" ?>"/>
                <p id="person-name"><?php echo $fa_name ?></p>
            </div>
        </div>
    </div>
    <div id="new-q">
        <form method="post">
            سوال
            <input type="text" name="question" value="<?php echo $question ?>" maxlength="150" placeholder="..."/>
            را از
            <select name="person">
                <?php

                     echo "<option selected hidden value=$en_name>$fa_name</option>";

                    for ($i = 0 ; $i < count($name_keys); $i++){
                        echo ("<option value=" . $name_keys[$i] . ">" . $names[$name_keys[$i]] . "</option>");
                    }

                ?>
            </select>
            <input type="submit" value="بپرس"/>
        </form>
    </div>
</div>
</body>
</html>