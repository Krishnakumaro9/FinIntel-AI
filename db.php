<?php

$conn = mysqli_connect("localhost", "root", "", "loanassist_ai");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

