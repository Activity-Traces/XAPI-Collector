<?php

    require "vendor/autoload.php";

    use App\Statements\CreateStatements;

    
    $test= new CreateStatements;
    
// les paramètres de connexion pour le serveur XAPI (exemple learning locker)

    $test->url = "http://localhost/data/xAPI/statements";
    $test->login = 'b56b263e2299704a5f1277ddd76cd523d44c6477';
    $test->password = '3917ebaf746e89c11a66bbd86ed3bb65831cd998';

// paramètres pour  se connecter à la BD Asker

    $test->dbhost='localhost';
    $test->dblogin='root';
    $test->dbpassword='';
    $test->dbdatabase='askerdatabase';
    


// test sur un apprenant visé et sur une période visée

    $test->user=2;
    $test->tmin='2018/01/01 00:00:00';
    $test->tmax='2020/01/01 00:00:00';
    
    $test->GetStatements();
    echo ($test->StatementJSON);
?>