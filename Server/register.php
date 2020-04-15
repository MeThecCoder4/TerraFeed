<?php
/* This script works by registering new user in the system after he manages to
   successfully enter valid data. */

include "SignupValidator.php";
$formValid = true;

echo "<a href=" . $_SERVER["PHP_SELF"] . ">Main page</a>";
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~ FORM AND VALIDATION ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~ LOGIN FIELD ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
echo "<form action=\"" . $_SERVER["PHP_SELF"] . "?subpage=register\">";
echo "<label for=\"login\">Login:</label><br>
      <input type=\"text\" id=\"login\" name=\"login\" maxlength=\"32\"
        value='" . (isset($_POST["login"]) ? $_POST["login"] : "") . "'><br>";
// Validate login
if (isset($_POST["btRegister"]))
{
    $status = (new SignupValidator())->validateLogin($_POST["login"]);

    if ($status == ErrorCode::INVALID_LOGIN)
    {
        $formValid = false;
        echo "<h3>Invalid login!</h3>";
    }
    else if ($status == ErrorCode::LOGIN_IN_USE)
    {
        $formValid = false;
        echo "<h3>Given login is already in use!</h3>";
    }
}

/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~ E-MAIL FIELD ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
echo "<label for=\"email\">E-mail:</label><br>
    <input type=\"email\" id=\"email\" name=\"email\" maxlength=\"128\"
    value='" . (isset($_POST["email"]) ? $_POST["email"] : "") . "'><br>";
// Validate email
if (isset($_POST["btRegister"]))
{
    if ((new SignupValidator())->validateEmail($_POST["email"]) == ErrorCode::INVALID_EMAIL)
    {
        $formValid = false;
        echo "<h3>Invalid email!</h3>";
    }
}

/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~ PASSWORD FIELD ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
echo "<label for=\"passwd\">Password:</label><br>
    <input type=\"password\" id=\"passwd\" name=\"passwd\" maxlength=\"64\"><br>";
// Validate password
if (isset($_POST["btRegister"]))
{
    if ((new SignupValidator())->validatePassword($_POST["passwd"]) == ErrorCode::INVALID_PASSWORD)
    {
        $formValid = false;
        echo "<h3>Invalid password!</h3>";
    }
}

/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~ REPEAT PASSWORD FIELD ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
echo "<label for=\"repasswd\">Repeat password:</label><br>
    <input type=\"password\" id=\"repasswd\" name=\"repasswd\" maxlength=\"64\"><br>";
// Validate repassword
if (isset($_POST["btRegister"]))
{
    if ((new SignupValidator())->validateRepassword($_POST["passwd"], $_POST["repasswd"])
        == ErrorCode::INVALID_REPASSWORD)
    {
        $formValid = false;
        echo "<h3>Repeated password has to be identical!</h3>";
    }
}

/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~ FEEDER ID FIELD ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
echo "<label for=\"feederId\">Feeder ID:</label><br>
    <input type=\"text\" id=\"feederId\" name=\"feederId\" maxlength=\"7\"
    value='" . (isset($_POST["feederId"]) ? $_POST["feederId"] : "") . "'><br>";
// Validate feeder ID
if (isset($_POST["btRegister"]))
{
    $status = (new SignupValidator())->validateFeederId($_POST["feederId"]);

    if ($status == ErrorCode::INVALID_FEEDERID)
    {
        $formValid = false;
        echo "<h3>Invalid feeder ID!</h3>";
    }
    else if ($status == ErrorCode::FEEDERID_IN_USE)
    {
        $formValid = false;
        echo "<h3>Given feeder ID has already been registered!</h3>";
    }
}

echo "<br><input type=\"submit\" name=\"btRegister\" value=\"Register\" formmethod=\"post\"></form>";

/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~ REGISTRATION LOGIC ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
// Create database connection
$dbConnection = mysqli_connect("127.0.0.1", "root", "", "terrafeed");

if (!$dbConnection)
    die("Could not connect to database!");

if (isset($_POST["btRegister"]))
{
    // If all fields passed validation stage
    if ($formValid == true)
    {
        $insertSql = "INSERT INTO Users (login, email, password, feederId) VALUES
                            ('" . trim($_POST["login"]) . "',
                             '" . trim($_POST["email"]) . "',
                             '" . trim($_POST["passwd"]) . "',
                             '" . trim($_POST["feederId"]) . "')";
        // Successfully registered new user
        if (mysqli_query($dbConnection, $insertSql))
        {
            $_SESSION["userLogin"] = $_POST["login"];
            mysqli_close($dbConnection);
            Header("Location: " . $_SERVER["PHP_SELF"]);
            exit();
        } // Error
        else
        {
            die("Could not register, please try again later.");
        }
    }
}

mysqli_close($dbConnection);
?>