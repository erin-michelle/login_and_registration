<?php

	session_start();
	require('connection.php');

	// var_dump($_SESSION);

	if(isset($_POST['action']) && $_POST['action'] == 'register')
	{
		//call to function
		register_user($_POST); //use the ACTUAL POST!
	}

	elseif(isset($_POST['action']) && $_POST['action'] == 'login')
	{
		login_user($_POST);
	}
	else // malicious navigation to process.php or someone is trying to log off
	{
		session_destroy();
		header('location: index.php');
		die();
	}


	function register_user($post)  //just a parameter called post
	{

		///-----------------begin validation checks------------------//

		$_SESSION['errors'] = array();

			if(empty($post['first_name']))
			{
				$_SESSION['errors'][] = "First name cannot be blank.";
			}
			if(empty($post['last_name']))
			{
				$_SESSION['errors'][] = "Last name cannot be blank.";
			}
			if(empty($post['password']))
			{
				$_SESSION['errors'][] = "Password field is required.";
			}
			if($post['password'] !== $post['confirm_password'])
			{
				$_SESSION['errors'][] = "Passwords must match.";
			}
			if(!filter_var($post['email'], FILTER_VALIDATE_EMAIL))
			{
				$_SESSION['errors'][] = "Please use a valid email adress.";
			}

			///-------------------end of validation checks.----------------//

			if(count($_SESSION['errors']) > 0)  //if I didn't have any errors at all.
			{
				header('Location: index.php');
				die();
			}
			else //now you need to insert the data into the database
			{
				$query = "INSERT INTO users (first_name, last_name, password, email, created_at, updated_at) 
					VALUES ('{$post['first_name']}', '{$post['last_name']}', '{$post['password']}', '{$post['email']}', NOW(), NOW())";
					
					run_mysql_query($query);
					$_SESSION['success_message'] = 'User successfully created.';
					header('Location: index.php');
					die();

					
			}
	}

	function login_user($post)  //just a parameter called post
	{
		$query = "SELECT * FROM users WHERE users.password = '{$post['password']}'
					AND users.email = '{$post['email']}'";
				$user = fetch_all($query); //go ahead and attempt to grab user with above credentials.
				if(count($user) > 0)
				{
					$_SESSION['user_id']=$user[0]['id'];
					$_SESSION['first_name'] = $user[0]['first_name'];
					$_SESSION['logged_in'] = TRUE;
					header('Location: success.php');
				}
				else
				{
					$_SESSION['errors'][] = "Cannot find a user with those credentials.";
					header('Location: index.php');
				}
	}
?>




