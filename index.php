<?php
$question = '';
$msg = 'سوال خود را بپرس';
$file_msg = fopen("messages.txt", "r");
$a = file_get_contents('people.json');
$b = json_decode(file_get_contents('people.json'));
$names_array = array();
$i = 1;
foreach ($b as $key => $value) {
    $names_array[$i] = $key;
    $i++;
}
$array_2 = array();
$j = 0;
while (!feof($file_msg)) {
    $array_2[$j] = fgets($file_msg);
    $j++;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $en_name = $_POST["person"];
    $question = $_POST["question"];
    $pa = hash('crc32b', $question . " " . $en_name);
    $ad = 16;
    $pa = hexdec($pa);
    $keynum = ($pa % $ad);
    $msg = $array_2[$keynum];
    foreach ($b as $key => $value) {
        if ($key == $en_name) {
            $fa_name = $value;
        }
    }
} else {
    $rand = array_rand($names_array);
    $en_name = $names_array[$rand];
    foreach ($b as $key => $value) {
        if ($key == $en_name) {
            $fa_name = $value;
        }
    }
}
$be = "/^آیا/iu";
$en = "/\?$/i";
$en2 = "/؟$/u";
if(! preg_match($be , $question) ) {
    $msg = "سوال درستی پرسیده نشده";
}
if(!(preg_match($en , $question) || preg_match($en2 , $question))){
    $msg = "سوال درستی پرسیده نشده";   
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
    <div id="title">
    <span id="label">
         <?php
            if ($question != "") {
                echo "پرسش:";
            }
         ?>
    </span>
    <span id="question"><?php echo $question ?></span>
    </div>
    <div id="container">
        <div id="message">
            <p><?php
                if ($question == "") {
                     echo "سوال خود را بپرس!";
                }
                else
                     echo $msg;
            ?></p>
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
            <select name="person" action="<?php echo $_SERVER['PHP_SELF'];?>">
                <?php
                    $b = json_decode($a);
                    foreach ($b as $key => $value) {
                        if ($en_name == $key) {
                            echo "<option value=$key selected> $value</option> ";
                        } 
                        else {
                            echo "<option value=$key > $value</option> ";
                        }
                    }
                ?>
            </select>
            <input type="submit" value="بپرس"/>
        </form>
    </div>
</div>
</body>
</html>