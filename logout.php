<?php
session_start();
if(isset($_SESSION['auth']) && $_SESSION['auth']['state'] == true){
    session_unset();
    header('location: /login');
}