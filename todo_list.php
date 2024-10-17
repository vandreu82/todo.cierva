<?php
$user = "example_user2";
$password = "password";
$database = "example_database";
$table = "todo_list";

try {
  $db = new PDO("mysql:host=localhost;dbname=$database", $user, $password);
  echo "<h2>TODO</h2><ul>";
  foreach($db->query("SELECT * FROM $table") as $row) {
    echo "<li>". $row['item_id'] .". ". $row['content'] . "</li>";
  }
  echo "</ul>";
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}