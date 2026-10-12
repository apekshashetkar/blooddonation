<?php
$conn = new mysqli("localhost", "root", "", "blood_donation");
if ($conn->connect_error) { die("Database connection failed: " . $conn->connect_error); }
session_start();
?>