<?php
 
include('config.php');
session_start();
 
if (isset($_POST['login'])) {
 
    $username = $_POST['username'];
    $password = $_POST['password'];
 
    $query = $connection->prepare("SELECT * FROM users WHERE USERNAME=:username");
    $query->bindParam("username", $username, PDO::PARAM_STR);
    $query->execute();
 
    $result = $query->fetch(PDO::FETCH_ASSOC);
 
    if (!$result) {
        echo '<p class="error">Username password combination is wrong!</p>';
    } else {
        if (password_verify($password, $result['password'])) {
            $_SESSION['user_id'] = $result['id'];
            echo '<p class="success">Congratulations, you are logged in!</p>';
            ?>          
            <style type="text/css">#form{
                display:none;
                }
            </style>

            <?php
            echo "<table style='border: solid 1px black;'>";
            echo "<tr><th>Name</th><th>Publisher</th><th>Caragory</th><th>Price</th></tr>";

            class TableRows extends RecursiveIteratorIterator 
            {
                function __construct($it) 
                {
                parent::__construct($it, self::LEAVES_ONLY);
                }

                function current() 
                {
                    return "<td style='width:150px;border:1px solid black;'>" . parent::current(). "</td>";
                }

                function beginChildren() 
                {
                    echo "<tr>";
                }

                function endChildren() 
                {
                    echo "</tr>" . "\n";
                }
            }

            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "bookstore";

            try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("SELECT name, publisher, catagory, price FROM books");
            $stmt->execute();

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
                echo $v;
            }
            } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            }
            $conn = null;
            echo "</table>";




        } else {
            echo '<p class="error">Username password combination is wrong!</p>';
        }
    }
}
 
?>

<div id = "form">
<form method="post" action="" name="signin-form">
    <div class="form-element">
        <label>Username</label>
        <input type="text" name="username" pattern="[a-zA-Z0-9]+" required />
    </div>
    <div class="form-element">
        <label>Password</label>
        <input type="password" name="password" required />
    </div>
    <button type="submit" name="login" value="login">Log In</button>
</form>
</div>