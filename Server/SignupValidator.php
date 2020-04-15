<?php

abstract class ErrorCode
{
    public const INVALID_LOGIN = 1;
    public const LOGIN_IN_USE = 2;
    public const INVALID_EMAIL = 3;
    public const INVALID_PASSWORD = 4;
    public const INVALID_REPASSWORD = 5;
    public const INVALID_FEEDERID = 6;
    public const FEEDERID_IN_USE = 7;
}

class SignupValidator
{
    public function validateLogin($login)
    {
        // Create database connection
        $dbConnection = mysqli_connect("127.0.0.1", "root", "", "terrafeed");

        if (!$dbConnection)
            die("Could not connect to database!");

        $sqlCheckLogin = "SELECT * FROM Users WHERE login='" . trim($login) . "'";
        $result = mysqli_query($dbConnection, $sqlCheckLogin);

        if ((strlen(trim($login)) == 0 ||
            strpos(trim($login), " ") != false))
        {
            mysqli_close($dbConnection);
            return ErrorCode::INVALID_LOGIN;
        } // Check if there already exists an user of that login
        else if (mysqli_num_rows($result))
        {
            mysqli_close($dbConnection);
            return ErrorCode::LOGIN_IN_USE;
        }

        mysqli_close($dbConnection);
        return 0;
    }

    public function validateEmail($email)
    {
        if (strlen(trim($email)) == 0 ||
            !(filter_var(trim($email), FILTER_VALIDATE_EMAIL)))
        {
            return ErrorCode::INVALID_EMAIL;
        }

        return 0;
    }

    public function validatePassword($password)
    {
        if (strlen(trim($password)) == 0 ||
            strpos(trim($password), " ") != false)
        {
            return ErrorCode::INVALID_PASSWORD;
        }

        return 0;
    }

    public function validateRepassword($password, $repassword)
    {
        if (trim($password) != trim($repassword))
            return ErrorCode::INVALID_REPASSWORD;

        return 0;
    }

    public function validateFeederId($feederId)
    {
        // Create database connection
        $dbConnection = mysqli_connect("127.0.0.1", "root", "", "terrafeed");

        if (!$dbConnection)
            die("Could not connect to database!");

        $sqlCheckFeederID = "SELECT * FROM Users WHERE feederId='" . trim($feederId) . "'";
        $result = mysqli_query($dbConnection, $sqlCheckFeederID);

        if (!$result)
            die("Unexpected problem occurred while connecting to the database!");

        if (strlen(trim($feederId)) == 0 ||
            preg_match('/[a-z1-9][a-z1-9]{6}/', trim($feederId)) === 0)
        {
            mysqli_close($dbConnection);
            return ErrorCode::INVALID_FEEDERID;
        }
        else if (strlen(trim($feederId)) > 0)
        {
            // Check if there already exists an user with given feeder ID
            if (mysqli_num_rows($result))
            {
                mysqli_close($dbConnection);
                return ErrorCode::FEEDERID_IN_USE;
            }
        }

        mysqli_close($dbConnection);
        return 0;
    }
}