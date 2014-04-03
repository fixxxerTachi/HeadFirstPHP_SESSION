<!DOCTYPE html PUBLIC "-//W4C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>ミスマッチ：サインアップ</title>
  <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
  <h3>ミスマッチ：サインアップ</h3>

<?php
  require_once('appvars.php');
  require_once('connectvars.php');

  // Connect to the database
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  if (isset($_POST['submit'])) {
    // Grab the profile data from the POST
    $username = mysqli_real_escape_string($dbc, trim($_POST['username']));
    $password1 = mysqli_real_escape_string($dbc, trim($_POST['password1']));
    $password2 = mysqli_real_escape_string($dbc, trim($_POST['password2']));

    if (!empty($username) && !empty($password1) && !empty($password2) && ($password1 == $password2)) {
      // Make sure someone isn't already registered using this username
      $query = "SELECT * FROM mismatch_user WHERE username = '$username'";
      $data = mysqli_query($dbc, $query);
      if (mysqli_num_rows($data) == 0) {
        // The username is unique, so insert the data into the database
        $query = "INSERT INTO mismatch_user (username, password, join_date) VALUES ('$username', SHA('$password1'), NOW())";
        mysqli_query($dbc, $query);

        // Confirm success with the user
        echo '<p>アカウントを作成しました。<a href="login.php">ログイン</a>することができます。</p>';

        mysqli_close($dbc);
        exit();
      }
      else {
        // An account already exists for this username, so display an error message
        echo '<p class="error">このユーザ名はすでに使われています。別のユーザ名をご利用ください。</p>';
        $username = "";
      }
    }
    else {
      echo '<p class="error">エラー：サインアップには全てのデータを入力する必要があります。パスワードは2回入力してください。</p>';
    }
  }

  mysqli_close($dbc);
?>

  <p>ユーザ名とパスワードを入力してミスマッチサイトにサインアップしてください。</p>
  <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <fieldset>
      <legend>登録情報</legend>
      <label for="username">ユーザ名：</label>
      <input type="text" id="username" name="username" value="<?php if (!empty($username)) echo $username; ?>" /><br />
      <label for="password1">パスワード：</label>
      <input type="password" id="password1" name="password1" /><br />
      <label for="password2">パスワード（もう一度）：</label>
      <input type="password" id="password2" name="password2" /><br />
    </fieldset>
    <input type="submit" value="サインアップ" name="submit" />
  </form>
</body> 
</html>
