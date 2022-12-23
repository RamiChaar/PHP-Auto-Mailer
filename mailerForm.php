<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Mailer App</title>
  <style>
    .error {
      color: red;
    }
  </style>
  <script>

    let registerDiv;
    let loginDiv;

    window.onload = function () {
      registerDiv = document.querySelector(".register")
      loginDiv = document.querySelector(".login")
    };

    function registerButtonClick() {
      registerDiv.style.display = "block"
      loginDiv.style.display = "none"
    }

    function goBackButtonClick() {
      registerDiv.style.display = "none"
      loginDiv.style.display = "block"
    }

    function logOutButtonClick() {
      window.location.reload();
    }
  </script>
</head>

<body>

  <?php
  //check if new server request or post request
  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //if post request connects to database

    //connect to your mailerDB database that you set up with the mailer.mysql file
    $serverName = "localhost";
    $userName = "databaseUsername";
    $password = "databasePassword";  
     
    $dbName = "mailDB";
    $connection = mysqli_connect($serverName, $userName, $password, $dbName);
    if (mysqli_connect_errno()) {
      echo "Failed to connection";
      exit();
    }

    //get username
    $username = $_POST['username'];

    //check if it is a email submission request
    if ($_POST['hour'] != null) {

      //get email and message information parse it and push it to database

      $sql = "SELECT idusers FROM users WHERE username = '$username'";
      $user = $connection->query($sql)->fetch_assoc()['idusers'];

      $email = $_POST['email'];
      $content = $_POST['content'];
      $date = $_POST['date'];
      $hourString = $_POST['hour'];
      $minuteString = $_POST['minute'];
      $meridian = $_POST['meridian'];
      $hour = 0;
      $minute = 0;
      $hourFiller = '';
      $minuteFiller = '';

      if ($minuteString == "thirty") {
        $minute = 30;
      }

      $numArray = array('one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven', 'twelve');

      $hour = array_search($hourString, $numArray) + 1;

      if ($meridian == 'PM' && $hour != 12) {
        $hour = $hour + 12;
      } else if ($meridian == 'AM' && $hour == 12) {
        $hour = $hour - 12;
      }

      if ($hour < 10) {
        $hourFiller = '0';
      }
      if ($minute < 10) {
        $minuteFiller = '0';
      }

      $datetime = $date . ' ' . $hourFiller . $hour . ':' . $minuteFiller . $minute . ':' . '00';

      $sql = "INSERT INTO messages (idusers, email, message, timestamp, sent) VALUES ('$user', '$email', '$content', '$datetime', 'false')";
      $result = $connection->query($sql);

      //redisplay email submission form
      echo "<div class='submit'></div>
            <button class='logOut' onclick='logOutButtonClick()'>Log Out</button>
            <form method='post'>
              <h2> Submit Mailing Information </h2>
              <h4> Email: </h4>
              <input type='text' name='email'>
              </br>
              <h4> Content: </h4>
              <textarea name = 'content'></textarea>
              </br>
              <h4> Date: </h4>
              <input type='date' name='date'>
              </br>
              <h4> Time: </h4>
              <select name='hour'>
                <option value='one'>1</option>
                <option value='two'>2</option>
                <option value='three'>3</option>
                <option value='four'>4</option>
                <option value='five'>5</option>
                <option value='six'>6</option>
                <option value='seven'>7</option>
                <option value='eight'>8</option>
                <option value='nine'>9</option>
                <option value='ten'>10</option>
                <option value='eleven'>11</option>
                <option value='twelve'>12</option>
              </select>
              <select name='minute'>
                <option value='zero'>00</option>
                <option value='thirty'>30</option>
              </select>
              <select name='meridian'>
                <option value='AM'>AM</option>
                <option value='PM'>PM</option>
              </select>
              </br>
              <input type='hidden' value='$username' name='username' />
              <button type='submit'>Submit</button>
            </form>
          </div>";
    } else if ($_POST['confirmPassword'] != null) {
      //this means that user was trying to submit a new account to register

      //check and make sure all password and usernames submitted are according to spec
      $password = $_POST['password'];
      $confirmPassword = $_POST['confirmPassword'];

      //check if passwords match
      if ($password != $confirmPassword) {
        echo "
            <div class='login' style='display:none'>
              <form method='post'>
                <h2> Login </h2>
                <h4> Username: </h4>
                <input type='text' name='username'>
                </br>
                <h4> Password: </h4>
                <input type='password' name='password'>
                </br>
                <button class='loginButton' type='submit'>Login</button>
              </form>
              <p> or </p>
              <button class='registerButton' onclick='registerButtonClick()'> Register Here </button>
            </div>
            
            <div class='register' style='display:block'>
              <button class='goBack' onclick='goBackButtonClick()'>Go back</button>
              <form method='post'>
                <h2> Register </h2>
                <h4> Username: </h4>
                <input type='text' name='username'>
                </br>
                <h4> Password: </h4>
                <input type='password' name='password'>
                </br>
                <h4> Confirm Password: </h4>
                <input type='password' name='confirmPassword'>
                </br>
                <button class='registerButton' name='registerSubmit'>Register</button>
              </form>
              <p class='error matchError'>Passwords do not match.</p>
            </div>
            ";
        exit();
      }

      //check if password is at least 8 characters
      if (strlen($password) < 8) {
        echo "
            <div class='login' style='display:none'>
              <form method='post'>
                <h2> Login </h2>
                <h4> Username: </h4>
                <input type='text' name='username'>
                </br>
                <h4> Password: </h4>
                <input type='password' name='password'>
                </br>
                <button class='loginButton' type='submit'>Login</button>
              </form>
              <p> or </p>
              <button class='registerButton' onclick='registerButtonClick()'> Register Here </button>
            </div>
            
            <div class='register' style='display:block'>
              <button class='goBack' onclick='goBackButtonClick()'>Go back</button>
              <form method='post'>
                <h2> Register </h2>
                <h4> Username: </h4>
                <input type='text' name='username'>
                </br>
                <h4> Password: </h4>
                <input type='password' name='password'>
                </br>
                <h4> Confirm Password: </h4>
                <input type='password' name='confirmPassword'>
                </br>
                <button class='registerButton' name='registerSubmit'>Register</button>
              </form>
              <p class='error lengthError'>Password must be at least 8 characters Long.</p>
            </div>
            ";
        exit();
      }

      $validPass = true;

      $hasNum = false;
      if (preg_match('~[0-9]+~', $password)) {
        $hasNum = true;
      }
      if (!$hasNum) {
        $validPass = false;
      }

      $hasLetter = false;
      $letters = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");
      for ($x = 0; $x < 26; $x++) {
        if (stripos($password, $letters[$x]) !== false) {
          $hasLetter = true;
        }
      }
      if (!$hasLetter) {
        $validPass = false;
      }

      $hasSpecial = false;
      $specials = array("`", "~", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "-", "_", "+", "=", "[", "{", "]", "}", "\\", "|", ";", ":", "'", "\"", ",", "<", ".", ">", "/", "?");
      for ($x = 0; $x <= 32; $x++) {
        if (stripos($password, $specials[$x]) !== false) {
          $hasSpecial = true;
        }
      }
      if (!$hasSpecial) {
        $validPass = false;
      }

      //checks if password contains all types of required characters
      if (!$validPass) {
        echo "
            <div class='login' style='display:none'>
              <form method='post'>
                <h2> Login </h2>
                <h4> Username: </h4>
                <input type='text' name='username'>
                </br>
                <h4> Password: </h4>
                <input type='password' name='password'>
                </br>
                <button class='loginButton' type='submit'>Login</button>
              </form>
              <p> or </p>
              <button class='registerButton' onclick='registerButtonClick()'> Register Here </button>
            </div>
            
            <div class='register' style='display:block'>
              <button class='goBack' onclick='goBackButtonClick()'>Go back</button>
              <form method='post'>
                <h2> Register </h2>
                <h4> Username: </h4>
                <input type='text' name='username'>
                </br>
                <h4> Password: </h4>
                <input type='password' name='password'>
                </br>
                <h4> Confirm Password: </h4>
                <input type='password' name='confirmPassword'>
                </br>
                <button class='registerButton' name='registerSubmit'>Register</button>
              </form>
              <p class='error typeError'>Password must contain at least one letter, one number, and one special character.</p>
            </div>
            ";
        exit();
      }

      $sql = "SELECT username, password, salt FROM users WHERE username = '$username'";
      $result = $connection->query($sql);
      $row = $result->fetch_assoc();

      //check to make sure username doesn't already exist
      if ($row["username"] != null) {
        echo "
            <div class='login' style='display:none'>
              <form method='post'>
                <h2> Login </h2>
                <h4> Username: </h4>
                <input type='text' name='username'>
                </br>
                <h4> Password: </h4>
                <input type='password' name='password'>
                </br>
                <button class='loginButton' type='submit'>Login</button>
              </form>
              <p> or </p>
              <button class='registerButton' onclick='registerButtonClick()'> Register Here </button>
            </div>
  
            <div class='register' style='display:block'>
              <button class='goBack' onclick='goBackButtonClick()'>Go back</button>
              <form method='post'>
                <h2> Register </h2>
                <h4> Username: </h4>
                <input type='text' name='username'>
                </br>
                <h4> Password: </h4>
                <input type='password' name='password'>
                </br>
                <h4> Confirm Password: </h4>
                <input type='password' name='confirmPassword'>
                </br>
                <button class='registerButton' name='registerSubmit'>Register</button>
              </form>
              <p class='error typeError'>This username already exists.</p>
            </div>
            ";
        exit();
      }

      //if everything passes store user in database and go to login

      //generate salt
      $salt = rand(100000000, 999999999);

      //hash password
      $hashedPass = hash("sha512", $salt . $password);

      //store in database
      $sql = "INSERT INTO users (username, password, salt) VALUES ('$username', '$hashedPass', $salt)";
      $result = $connection->query($sql);

      echo "
            <div class='login' style='display:block'>
              <form method='post'>
                <h2> Login </h2>
                <h4> Username: </h4>
                <input type='text' name='username'>
                </br>
                <h4> Password: </h4>
                <input type='password' name='password'>
                </br>
                <button class='loginButton' type='submit'>Login</button>
              </form>
              <p> or </p>
              <button class='registerButton' onclick='registerButtonClick()'> Register Here </button>
            </div>
  
            <div class='register' style='display:none'>
              <button class='goBack' onclick='goBackButtonClick()'>Go back</button>
              <form method='post'>
                <h2> Register </h2>
                <h4> Username: </h4>
                <input type='text' name='username'>
                </br>
                <h4> Password: </h4>
                <input type='password' name='password'>
                </br>
                <h4> Confirm Password: </h4>
                <input type='password' name='confirmPassword'>
                </br>
                <button class='registerButton' name='registerSubmit'>Register</button>
              </form>
            </div>
            ";
      exit();

    } else {

      // this means user is trying to login 

      $password = $_POST['password'];

      $validUser = false;

      //get hashed password and salt from database
      $sql = "SELECT username, password, salt FROM users WHERE username = '$username'";
      $result = $connection->query($sql);
      $row = $result->fetch_assoc();

      $salt = $row["salt"];
      $databasePassword = $row["password"];

      //check if inputed password matches password provided
      if (hash("sha512", $salt . $password) == $databasePassword) {
        $validUser = true;
      }

      //if match login and display email message form
      if ($validUser) {
        $_POST['username'] = $username;
        echo "<div class='submit'></div>
            <button class='logOut' onclick='logOutButtonClick()'>Log Out</button>
            <form method='post'>
              <h2> Submit Mailing Information </h2>
              <h4> Email: </h4>
              <input type='text' name='email'>
              </br>
              <h4> Content: </h4>
              <textarea name = 'content'></textarea>
              </br>
              <h4> Date: </h4>
              <input type='date' name='date'>
              </br>
              <h4> Time: </h4>
              <select name='hour'>
              <option value='one'>1</option>
              <option value='two'>2</option>
              <option value='three'>3</option>
              <option value='four'>4</option>
              <option value='five'>5</option>
              <option value='six'>6</option>
              <option value='seven'>7</option>
              <option value='eight'>8</option>
              <option value='nine'>9</option>
              <option value='ten'>10</option>
              <option value='eleven'>11</option>
              <option value='twelve'>12</option>
              </select>
              <select name='minute'>
                <option value='zero'>00</option>
                <option value='thirty'>30</option>
              </select>
              <select name='meridian'>
                <option value='AM'>AM</option>
                <option value='PM'>PM</option>
              </select>
              </br>
              <input type='hidden' value='$username' name='username' />
              <button type='submit'>Submit</button>
            </form>
          </div>";
        $_POST['username'] = $username;

      } else {
        //if not stay on login page with error message
        echo "
            <div class='login'>
              <form method='post'>
                <h2> Login </h2>
                <h4> Username: </h4>
                <input type='text' name='username'>
                </br>
                <h4> Password: </h4>
                <input type='password' name='password'>
                </br>
                <button class='loginButton' type='submit'>Login</button>
              </form>
              <p style='color:red'> Invalid Username Or Password. </p>
              <p> or </p>
              <button class='registerButton' onclick='registerButtonClick()'> Register Here </button>
            </div>
            
            <div class='register'  style='display:none'>
              <button class='goBack' onclick='goBackButtonClick()'>Go back</button>
              <form method='post'>
                <h2> Register </h2>
                <h4> Username: </h4>
                <input type='text' name='username'>
                </br>
                <h4> Password: </h4>
                <input type='password' name='password'>
                </br>
                <h4> Confirm Password: </h4>
                <input type='password' name='confirmPassword'>
                </br>
                <button class='registerButton' name='registerSubmit'>Register</button>
              </form>
            </div>
            ";
      }
    }

  } else {
    //if new display login
    echo "
        <div class='login'>
          <form method='post'>
            <h2> Login </h2>
            <h4> Username: </h4>
            <input type='text' name='username'>
            </br>
            <h4> Password: </h4>
            <input type='password' name='password'>
            </br>
            <button class='loginButton' type='submit'>Login</button>
          </form>
          <p> or </p>
          <button class='registerButton' onclick='registerButtonClick()'> Register Here </button>
        </div>
        
        <div class='register' style='display:none'>
          <button class='goBack' onclick='goBackButtonClick()'>Go back</button>
          <form method='post'>
            <h2> Register </h2>
            <h4> Username: </h4>
            <input type='text' name='username'>
            </br>
            <h4> Password: </h4>
            <input type='password' name='password'>
            </br>
            <h4> Confirm Password: </h4>
            <input type='password' name='confirmPassword'>
            </br>
            <button class='registerButton' name='registerSubmit'>Register</button>
          </form>
        </div>
        ";
  }
  ?>

</body>

</html>