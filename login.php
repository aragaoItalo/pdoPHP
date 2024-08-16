<?php 

session_start();

//VERIFICAR SE O USER ESTÁ LOGADO
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcome.php");
    exit
}

REQUIRE_ONCE "config.php";

//DEFINIR VARIAVEIS E INICIALIZAR COM VALORES VAZIOS
$username = $password = "";
$username_err = $password_err = $login_err = "";

//PROCESSAR DADOS DO FORM QUANDO ELE É ENVIADO 
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    //VERIFICAR SE O NOME DO USER ESTÁ VAZIO
    if(empty(trim($_POST["username"]))){
        $username_err = "Por favor, insira o nome do usuário.";
    } else{
        $username = trim($_POST["username"]);
    }

    //VERIFICAR SE A SENHA ESTÁ VAZIA
    if(empty(trim($_POST["PASSWORD"]))){
        $password_err = "Por favor, insira sua senha.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    //VALIDAR CREDENCIAIS
    if(empty($username_err) && empty($password_err)){
        //PREPARE UMA DECLARAÇÃO SELECIONADA 
        $sql = "SELECT id, username, password FROM users WHERE username = :username";

        if($stmt = $pdo->prepare($sql)){
            //VINCULE AS VARIAVEIS À INSTRUÇÃO PREPARADA COM PARAMETROS 
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);

            //DEFINIR PARAMETROS 
            $param_username = trim($_POST["username"]);

            //TENTAR EXECUTAR A DECLARAÇÃO PREPARADA
            if($stmt->execute()){
                //VERIFICAR SE O NOME DE USER EXISTE, SE SIM, VERIFICAR SENHA
                if($stmt->rowCount() === 1){
                    if($row = $stmt->fetch()){
                        $id = $row["id"];
                        $username = $row["username"];
                        $hashed_password = $row["password"];
                        if(password_verify($password, $hashed_password)){
                            //A SENHA ESTÁ CORRETA, ENTÃO INICIAR NOVA SESSÃO
                            session_start();
                        }
                    }
                }
            }
        }
    }
}



?>