<?php 
	$connection = mysqli_connect('localhost','root','','simple_todo');
	 
	 $id = '';

	if (isset($_POST['todo']) && empty($_POST['id']) ) {
			$todo =	$_POST['todo'];
			if (!empty($todo)) {
				if (insertTodo($todo,$connection) == true) {
				echo "<script>alert('Todos Inserted');</script>";
			 }
			}else{
				echo "<script>alert('Please Inserted Todo');</script>";
			}
			
			
	}

	if (isset($_POST['todo']) && !empty($_POST['id']) ) {
		$id  =  $_POST['id'];
	  	$todo = $_POST['todo'];
	  	 if(UpdateTodo($id,$todo,$connection)){
			   echo "<script>alert('Todos Updated');</script>";
		   }
			
	}

	if (isset($_GET['delete_id'])) {
		$id = $_GET['delete_id'];
		if (DeleteTodo($id,$connection) == true) {
			echo "<script>alert('Todos Deleted');</script>";
		}
		
	}
	if (isset($_GET['marking_id'])) {
		$id = $_GET['marking_id'];
		if (CompleteTodo($id,$connection)== true) {
			echo "<script>alert('Todos Complete Successfully');</script>";
		}
	}

	function InsertTodo($todo,$connection)
	{
		
		$query = "INSERT INTO todos set todo = '$todo' ";
		$result = mysqli_query($connection,$query);
		if ($result == true) {
			return true;
		}else{
			return false;
		}
	}

	function DeleteTodo($id,$connection)
	{
		$query = "Delete from todos where id = '$id' ";
		$result = mysqli_query($connection,$query);
		if ($result == true) {
			return true;
		}else{
			return false;
		}
	}


	function CompleteTodo($id,$connection)
	{
	    $query = "UPDATE todos set completed = 1 where id = '$id'";
		$result = mysqli_query($connection,$query);
		if ($result == true) {
			return true;
		}else{
			return false;
		}

	}
	if(isset($_POST['update'])){
		$id = $_POST['update_id'];
		$data = GetTodo($id,$connection);
	}
		function GetTodo($id,$connection)
	{
		$query = "select * from todos where id = '$id' ";
		$result = mysqli_query($connection,$query);
		if ($result == true) {
			return	mysqli_fetch_assoc($result);
		}else{
			return false;
		}

	}
		function UpdateTodo($id,$todo,$connection)
	{
		$query = "Update todos set todo = '$todo' where id = '$id' ";
		$result = mysqli_query($connection,$query);
		if (mysqli_error($connection)) {
			die(mysqli_error($connection));
		}
	}
 ?>
 

<!DOCTYPE html>
<html>
<head>
	<title>A Basic Todo</title>
		<style type="text/css">
		input[type="text"]{
			padding: 10px 20px 10px 20px;
			width: 400px;
			margin-top: 5px;
		}
		.wrapper{
			width:40%;
			margin: 0 auto;
			border-left: 2px solid #ddd;
			border-right: 2px solid #ddd;
			border-top: 2px solid #ddd;
			background-color:#bebebe;
		}
		.form {
			display: inline-block;
		}
		a {
			text-decoration:none;
			color: #3e3e3e;
		}
		.button{
			background-color: #4CAF50; /* Green */
			border: none;
			color: white;
			padding: 10px 16px;
			text-align: center;
			text-decoration: none;
			display: inline-block;
			font-size: 12px;
			margin: 4px 2px;
			cursor: pointer;
		}
		
	</style>
</head>
<body>
<div class="wrapper">
	<form method="post" action="index.php">
		<div>
			<center>
			<input type="text" name="todo" placeholder="create new todo" value="<?php if (isset($_POST['update_id'])) {echo $data['todo'];}  ?>" required>
			<input type="hidden" name="id" value="<?php if (isset($_POST['update_id'])) {echo $data['id'];}  ?>" >
			<input class="button" type="submit" value="Submit">
			</center>
		</div>
	</form>
	<br><br>

	<?php 
	$query = "select * from todos order by id desc";
	$result = mysqli_query($connection,$query);
	$row = mysqli_fetch_all($result, MYSQLI_ASSOC);
	foreach ($row as $todos) { ?>
		<center>
		<?php echo $todos['todo'];  ?>
	  		<button><a href='index.php?delete_id=<?php echo $todos['id']; ?>'>Delete</a></button>
				<form class="form" method="post" action="">
					<input type="hidden" name="update_id" value="<?php  echo $todos['id']; ?>">
					<input type="submit" value="Update" name="update">
				</form>
	   <?php if ($todos['completed'] == 1) {
	  	echo "Completed";
	  }else{ ?>
	  			<button><a href='index.php?marking_id=<?php echo $todos['id']; ?>'>Mark complete</a></button>
	 <?php } ?>
		<hr>
	</center>
<?php } ?>
</div>
</body>
</html>
