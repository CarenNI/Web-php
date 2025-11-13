<?php
//preciso chamar para poder mexer na sessão.
session_start();      // inicia a sessão

//limpa as variáveis salvas na sessão (como user_id e user_nome).
session_unset();      // limpa todas as variáveis da sessão

// destrói a sessão inteira
session_destroy();    

// manda de volta para a tela de login
//depois de sair, mando o usuário de volta pro login.
header("Location: login.php");
exit;


//“No logout, primeiro dou session_start() para acessar a sessão.
//Depois uso session_unset() para limpar as variáveis da sessão
//e session_destroy() para finalizar a sessão.
//Por fim uso header('Location: login.php') para redirecionar o usuário para o login.”