<?php
/* If user successfully signs in, this script shall create new entry: "userLogin"
   in $_SESSION array. Otherwise it will close database connection and display an error. */

/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~ LOGOUT LOGIC ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
if(isset($_SESSION["userLogin"]))
{
    unset($_SESSION["userLogin"]);
    Header("Location:" . $_SERVER["PHP_SELF"]);
    exit();
}

/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~ LOGIN FORM ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
echo "<a href=" . $_SERVER["PHP_SELF"] . ">Main page</a>";
echo "<form action=\"" . $_SERVER["PHP_SELF"] . "?subpage=login\">";
echo <<<LOGIN_FORM
    <label for="login">Login:</label><br>
    <input type="text" id="login" name="login" maxlength="32"><br>
    <label for="passwd">Password:</label><br>
    <input type="password" id="passwd" name="passwd" maxlength="64"><br>
    <input type="submit" value="Login" name="btLogin" formmethod="post"></form>
LOGIN_FORM;

/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~ LOGIN LOGIC ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
if (isset($_POST["btLogin"]))
{
    // Create database connection
    $dbConnection = mysqli_connect("127.0.0.1", "root", "", "terrafeed");

    if (!$dbConnection)
        die("Could not connect to database!");

    $sql = "SELECT * FROM Users WHERE login='" . $_POST["login"] . "'";
    $result = mysqli_query($dbConnection, $sql);

    // Check if user exists in database
    if (mysqli_num_rows($result) > 0)
    {
        $userData = mysqli_fetch_assoc($result);
        // Check if user password matches with that entered
        if ($userData["password"] == $_POST["passwd"])
        {
            $_SESSION["userLogin"] = $_POST["login"];
            mysqli_close($dbConnection);
            Header("Location: " . $_SERVER["PHP_SELF"]);
            exit();
        }
        else
        {
            echo "<h3>Invalid password!</h3>";
        }
    } // If the user doesn't exist in database
    else
    {
        echo "<h3>Given user does not exist!</h3>";
    }

    mysqli_close($dbConnection);
}
?>