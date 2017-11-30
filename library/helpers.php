<?php 

function get_pdo(){
    $defaults = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ];

    $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_DBNAME, 
        DB_USER, DB_PASSWORD, 
        $defaults
    );

    return $pdo;
}

function dump($data){

    echo '<pre>';
    print_r($data);
    echo '</pre>';
}

function getFlashMessage(){

    if( !isset($_SESSION) )
    {
    
        throw new Exception("Attention getFlashMessage les sessions ne sont pas activées");
    
    }

    if(!empty($_SESSION['flash']) )
    {
        
        $message = sprintf('<div class="%s"><strong>%s</strong></div>',
            $_SESSION['flash']['type'],
            $_SESSION['flash']['message']
        );
            
            
        unset($_SESSION['flash']);
        
        return $message ;
    
    }

}


function setFlashMessage($message, $type = 'success'){

    if( !isset($_SESSION) )
    {
        throw new Exception("Attention getFlashMessage les sessions ne sont pas activées");
    }
    
    $_SESSION['flash'] = [
    
        'message' => $message,
        'type' => $type
    ];

}


function hasFlashMessage(){


    if( isset($_SESSION['flash']) ) return true;
    
    
    return false;

}