<?php

function index(){
		include __DIR__ . '/../views/front/auth.php';
}

function add_user(){

	$pdo = get_pdo();

	if( !empty($_POST) && $_POST['password']=$_POST['passcheck']) {

		$pseudo = htmlspecialchars($_POST['pseudo']);
		$password = htmlspecialchars($_POST['password']);
		$email = htmlspecialchars($_POST['email']);

		$prepare = $pdo->prepare("INSERT INTO `users` (`pseudo`, `password`, `email`) VALUES (?,?,?) ;");
    
        $prepare->bindValue(1, $pseudo);
        $prepare->bindValue(2, $password);
        $prepare->bindValue(3, $email);

        $prepare->execute();
	}		
	include __DIR__ . '/../views/back/add_user.php';
}

function auth(){

	$pdo = get_pdo();
	$flagToken = false;
    
    $token = $_POST['token']; 
    
    if( !empty($token) )
    {
    	foreach( range(0,TOKEN_TIME) as $t){
        	if( $token == md5( date('Y-m-d h:i:00', time() - 60*$t ) . SALT ) )
            {
            	$flagToken = true;
            }
        }
    }

	if ($flagToken == false)
    {
    	header('Location: /'); // redirection vers la page / authentification pour nous
        
        exit; // stop l'exécution des scripts 
    
    }
    
    
    // authentification
    
    $email = $_POST['email'];
    $password = $_POST['password'];

    $rules = [
        'email' => FILTER_VALIDATE_EMAIL,
        'password' => [
        	
            'filter' => FILTER_CALLBACK,
            'options' => function($password){
            
            	if( strlen($password) < 4 ){
                	return false;
                }
                return $password;
            }
        ]
    ];
    
    $sanitize = filter_input_array(INPUT_POST, $rules);
    
    $prepare = $pdo->prepare('SELECT id, password FROM users WHERE email = ?');
    
    $prepare->bindValue(1,$sanitize['email']);
    $prepare->execute();
    
    $stmt = $prepare->fetch();
	
    if( password_verify($sanitize['password'], $stmt['password']) ){
    	
    	// regenérer le cookie de session
        session_regenerate_id(true); 
    
    	$_SESSION['auth'] = $stmt['id'];
        
        // redirection vers la page d'administration sécuriser cette page 
        
        header('Location: admin');
        exit;
    }else{
    	header('Location: /');
    	setFlashMessage('Mot de passe ou Email incorrect');
    	exit;
    }
}