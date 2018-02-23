<?php
/**
 * Created by IntelliJ IDEA.
 * User: josso_t
 * Date: 04/05/2017
 * Time: 15:36
 */

function getPDO() {
    $dsn = 'mysql:host=localhost;dbname=420px';
    $user = 'root';
    $password = '';
    return new PDO($dsn, $user, $password);
}

function getUserFromPseudo($pseudo, $connection) {
    if (verify_pseudo($pseudo)) {
        $select = $connection->prepare('SELECT * FROM user WHERE pseudo = ? LIMIT 1;');
        $select->execute(array($pseudo));
        $select->setFetchMode(PDO::FETCH_OBJ);
        return $select->fetch();
    }
    return false;
}

function getImageFromId($id, $connection) {
    if (verify_id($id)) {
        $select = $connection->prepare("SELECT * FROM image WHERE id = ? LIMIT 1");
        $select->execute(array($id));
        $select->setFetchMode(PDO::FETCH_OBJ);
        return $select->fetch();
    }
    return false;
}

function getImagesFromId($id, $connection) {
    if (verify_id($id)) {
        $select = $connection->prepare("SELECT * FROM user JOIN image WHERE user.id = ? AND image.userId = user.id");
        $select->execute(array($id));
        $select->setFetchMode(PDO::FETCH_OBJ);
        return $select;
    }
    return false;
}

function getImagesFromPseudo($pseudo, $connection) {
    if (verify_pseudo($pseudo)) {
        $select = $connection->prepare("SELECT * FROM user JOIN image WHERE user.pseudo = ? AND image.userId = user.id");
        $select->execute(array($pseudo));
        $select->setFetchMode(PDO::FETCH_OBJ);
        return $select;
    }
    return false;
}

function deleteImageFromId($id, $connection) {
    if (verify_id($id)) {
        $select = $connection->prepare("DELETE FROM image WHERE id = ?");
        $select->execute(array($id));
        return $select->fetch();
    }
    return false;
}

function test_input($data)
{
    return $data;
    /*$data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;*/
}

function verify_user($pseudo, $password, $connection)
{
    if (verify_pseudo($pseudo)) {
        $signedup = $connection->prepare("SELECT * FROM user WHERE pseudo = ? LIMIT 1");
        $signedup->execute(array($pseudo));
        $signedup->setFetchMode(PDO::FETCH_OBJ);
        $found = $signedup->fetch();
        if (!empty($found) && password_verify($password, $found->password)) {
            $_SESSION["logged_in"] = true;
            $_SESSION["pseudo"] = $pseudo;
            header('Location: home');
        } else {
            return '<p class="msg-error">Wrong credentials</p>';
        }
    }
    return "";
}

function add_user($pseudo, $password, $connection)
{
    if (verify_pseudo($pseudo)) {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $insert = $connection->prepare("INSERT INTO user VALUES (0, ?, ?)");
        $nbInsert = $insert->execute(array($pseudo, $hash));
        if ($nbInsert == 1) {
            return '<div class="msg-confirm">User added !</div>';
        } else {
            return '<div class="msg-error">User can\'t be added :(</div>';
        }
    }
    return '<div class="msg-error">Pseudo must be alphanumeric</div>';
}

function verify_pseudo($string) {
    return ctype_alnum($string);
}

function verify_id($id) {
    return ctype_digit($id);
}

function verify_password($string) {
    return preg_match("#.*^(?=.{8,24})(?=.*[a-z])(?=.*[0-9])(?=.*\W).*$#", $string);
    //return preg_match("#.*^(?=.{8,24})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#", $string);
}

function verify_signup_form($submit, $pseudo, $password, $confirm_password, $connection) {
    if ($submit !== "") {
        if ($pseudo === '') return '<p class="msg-error">Please provide a pseudo</p>';
        else if ($password === '') return '<p class="msg-error">Please provide a password</p>';
        else if ($confirm_password === '') return '<p class="msg-error">Please confirm the password</p>';
        else if ($password !== $confirm_password) return '<p class="msg-error">The confirmation must be identical</p>';
        else if (!verify_pseudo($pseudo)) return '<p class="msg-error">Pseudo must be alphanumeric</p>';
        else if (!verify_password($password)) {
            return '<ul class="msg-error password-rules">Password rules:'
                . '<li class="password-rule">Between 8 and 24 characters</li>'
                . '<li class="password-rule">Contains a lowercase character</li>'
                //. '<li>Contains an uppercase character</li>'
                . '<li class="password-rule">Contains a special character</li>'
                . '</ul>';
        }
        else return add_user($pseudo, $password, $connection);
    }
    else {
        return "";
    }
}

function outputRSS($pseudo, $connection) {
    if (verify_pseudo($pseudo)) {
        $timestamp = gmdate('r', strtotime(date("D, d M Y H:i:s", time())));
        echo '<?xml version="1.0" encoding="UTF-8"?>
            <rss version="2.0">
            <channel>
                <title>' . $pseudo . '</title>
                <description>User ' . $pseudo . ' RSS flux from 420px</description>
                <lastBuildDate>' . $timestamp . '</lastBuildDate>
                <link>' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . '/' . '</link>';
        $select = getImagesFromPseudo($pseudo, $connection);
        while ($image = $select->fetch(PDO::FETCH_OBJ)) {
            echo '<item>
                      <title>' . $image->id . '</title>
                      <description>Image number ' . $image->id . '</description>
                      <link>' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . '/420px/' . substr($image->location, 2) . '</link>
                  </item>';
        }
        echo '</channel></rss>';
    }
}

function outputImages($pseudo, $connection) {
    if (verify_pseudo($pseudo)) {
        echo "<h3>User $pseudo</h3>";
        echo "<ul id=\"gallery\">";
        $select = getImagesFromPseudo($pseudo, $connection);
        $i = 0;
        while ($image = $select->fetch()) {
            $i = $i + 1;
            echo "<li>"
                . "<a href='image?img=" . $image->id . "'>"
                . "<img src='$image->location'/>"
                . "</a>"
                . "</li>";
        }
        echo "</ul>";
        if ($i === 0) {
            echo '<p class="msg-error">User not found</p>';
        }
    }
}

function outputSearchedImages($pseudo, $connection) {
    if (verify_pseudo($pseudo)) {
        echo "<h3>User $pseudo</h3>";
        echo "<ul id=\"gallery\">";
        $select = getImagesFromPseudo($pseudo, $connection);
        $i = 0;
        while ($image = $select->fetch()) {
            $i = $i + 1;
            echo "<li>"
                . "<img src='$image->location'/>"
                . "</a>"
                . "</li>";
        }
        echo "</ul>";
        if ($i === 0) {
            echo '<p class="msg-error">User not found</p>';
        }
    }
}

function outputColorSearchedImages($r, $g, $b, $pct, $connection) {
    echo "<h3>Color (R : $r  G : $g  B : $b)</h3>";
    echo "<ul id=\"gallery\">";

    $pct = verify_percentage($pct);

    //OK mais bof
    $select = $connection->prepare("SELECT * FROM image WHERE ((abs((avgR - ?)) / 255 + abs((avgG - ?)) / 255 + abs((avgB - ?)) / 255) / 3) * 100 < '$pct'");
    $select->execute(array($r, $g, $b));

    //COOL mais pas ok :(
    //$select = $connection->prepare("SELECT * FROM image WHERE sqrt(((avgR - ?) * (avgR - ?)) + ((avgG - ?) * (avgG - ?)) + ((avgB - ?)(avgB - ?))) < '$delta'");
    //$select->execute(array($r, $r, $g, $g, $b, $b));

    $select->setFetchMode(PDO::FETCH_OBJ);
    $i = 0;
    while ($image = $select->fetch()) {
        $i = $i + 1;
        echo "<li>"
            . "<img src='$image->location'/>"
            . "</a>"
            . "</li>";
    }
    echo "</ul>";
    if ($i === 0) {
        echo '<p class="msg-error">Color not found (with ' . $pct . '% accuracy)</p>';
    }
}

function verify_percentage($pct) {
    if (ctype_digit($pct)) {
        if ($pct < 0)
            return 0;
        else if ($pct > 100)
            return 100;
        else
            return $pct;
    }
    return 20;
}

?>