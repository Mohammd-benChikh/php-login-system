<?php include_once './includes/header.php' ?>

<?php
if(!isset($_SESSION['auth']) && $_SESSION['auth']['state'] == false){
    header("location: /login.php");
}

?>
<div class="container">
    <div class="user-panel" >
    <div class="card">
        <p>Welcome <?php echo $_SESSION['auth']['user']['username']?> your are now logged in</p>
    </div>
    </div>
  
</div>
<?php include_once './includes/footer.php' ?>