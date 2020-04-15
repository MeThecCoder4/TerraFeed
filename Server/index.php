<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>TerraFeed</title>
    <meta charset="utf-8">
</head>
<body>
<!--HEADER-->
<header>
    <ul>
        <?php
        echo "<li><a href=\"" . $_SERVER['PHP_SELF'] . "?subpage=login\">";

        if (isset($_SESSION["userLogin"]))
        {
            echo "Logout";
        }
        else
        {
            echo "Login";
        }

        echo "</a></li>";
        ?>
        <li><a href="<?= $_SERVER['PHP_SELF'] ?>?subpage=register">Register</a></li>
    </ul>
</header>
<!--MAIN SECTION-->
<div class="main-section">
    <?php
    if (isset($_GET["subpage"]))
    {
        switch ($_GET["subpage"])
        {
            case "login":
                include "login.php";
                break;
            case "register":
                include "register.php";
                break;
        }
    }
    ?>
</div>
</body>
</html>
