<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>ミスマッチ：プロフィールの参照</title>
  <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
  <h3>ミスマッチ：プロフィールの参照</h3>

<?php
  require_once('appvars.php');
  require_once('connectvars.php');

  // Connect to the database
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  // Grab the profile data from the database
  if (!isset($_GET['user_id'])) {
    $query = "SELECT username, last_name, first_name, gender, birthdate, city, state, picture FROM mismatch_user WHERE user_id = '$user_id'";
  }
  else {
    $query = "SELECT username, last_name, first_name, gender, birthdate, city, state, picture FROM mismatch_user WHERE user_id = '" . $_GET['user_id'] . "'";
  }
  $data = mysqli_query($dbc, $query);

  if (mysqli_num_rows($data) == 1) {
    // The user row was found so display the user data
    $row = mysqli_fetch_array($data);
    echo '<table>';
    if (!empty($row['username'])) {
      echo '<tr><td class="label">ユーザ名：</td><td>' . $row['username'] . '</td></tr>';
    }
    if (!empty($row['last_name'])) {
      echo '<tr><td class="label">姓：</td><td>' . $row['last_name'] . '</td></tr>';
    }
    if (!empty($row['first_name'])) {
      echo '<tr><td class="label">名：</td><td>' . $row['first_name'] . '</td></tr>';
    }
    if (!empty($row['gender'])) {
      echo '<tr><td class="label">性別：</td><td>';
      if ($row['gender'] == '男') {
        echo '男性';
      }
      else if ($row['gender'] == '女') {
        echo '女性';
      }
      else {
        echo '?';
      }
      echo '</td></tr>';
    }
    if (!empty($row['birthdate'])) {
      if (!isset($_GET['user_id']) || ($user_id == $_GET['user_id'])) {
        // Show the user their own birthdate
        echo '<tr><td class="label">誕生日：</td><td>' . $row['birthdate'] . '</td></tr>';
      }
      else {
        // Show only the birth year for everyone else
        list($year, $month, $day) = explode('-', $row['birthdate']);
        echo '<tr><td class="label">生年：</td><td>' . $year . '</td></tr>';
      }
    }
    if (!empty($row['city']) || !empty($row['state'])) {
      echo '<tr><td class="label">住所：</td><td>' . $row['city'] . ', ' . $row['state'] . '</td></tr>';
    }
    if (!empty($row['picture'])) {
      echo '<tr><td class="label">写真：</td><td><img src="' . MM_UPLOADPATH . $row['picture'] .
        '" alt="Profile Picture" /></td></tr>';
    }
    echo '</table>';
    if (!isset($_GET['user_id']) || ($user_id == $_GET['user_id'])) {
      echo '<p><a href="editprofile.php">プロフィール</a>を編集しますか？</p>';
    }
  } // End of check for a single row of user results
  else {
    echo '<p class="error">エラー：問題が発生したためプロフィールにアクセス出来ませんでした。</p>';
  }

  mysqli_close($dbc);
?>
</body> 
</html>
