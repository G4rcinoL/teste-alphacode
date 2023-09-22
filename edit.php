<?php
if (!empty($_GET['id'])) {
    $pdo = new PDO("mysql:host=localhost;dbname=db_mvc", "root", "");

    $id = $_GET['id'];

    $sqlSelect = "SELECT * FROM pessoa WHERE id = :id";

    $result = $pdo->prepare($sqlSelect);
    $result->bindParam(':id', $id, PDO::PARAM_INT);
    $result->execute();

    if ($result->rowCount() > 0) {
        $user_data = $result->fetch(PDO::FETCH_ASSOC);
        $nome = $user_data['nome'];
        $data_nascimento = date('d/m/Y', strtotime($user_data['data_nascimento']));
        $email = $user_data['email'];
        $profissao = $user_data['profissao'];
        $telefone_contato = preg_replace ('/[^0-9+]/', '', ($user_data)['telefone_contato']);
        $celular_contato = $user_data['celular_contato'];
        $numero_whatsapp = isset($user_data['numero_whatsapp']) ? 1 : 0;
        $notificacao_email = isset($user_data['notificacao_email']) ? 1 : 0;
        $notificacao_sms = isset($user_data['notificacao_sms']) ? 1 : 0;

        
    } else {
        header('Location: formulario.php');
        exit; 
    }
}

if (isset($_POST['update'])) 
{
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $data_nascimento = $_POST['data_nascimento'];
        $result=explode('/',$data_nascimento);
        $dia = $result[0];
        $mes = $result[1];
        $ano = $result[2];
    $data_nascimento = $ano.'-'.$mes.'-'.$dia;
    $email = $_POST['email'];
    $celular_contato = preg_replace('/[^0-9+]/', '', $_POST['celular_contato']);

    $pdo = new PDO("mysql:host=localhost;dbname=db_mvc", "root", "");

    
    $sql = "UPDATE pessoa SET nome = :nome, data_nascimento = :data_nascimento, email = :email, celular_contato = :celular_contato WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
    $stmt->bindParam(':data_nascimento', $data_nascimento, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':celular_contato', $celular_contato, PDO::PARAM_STR);

    if ($stmt->execute()) {
        
        header('Location: formulario.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Edição de Contato</title>
</head>

<body>
    <header>
        <img src="assets/logo_alphacode.png" alt="">
        <h1 id="titulo">Edição de Contato</h1>
    </header>

    <form action="edit.php?id=<?php echo $id; ?>" method="POST">
        <div id="container">
            <div class="container-input">
                <input type="text" name="nome" value="<?php echo htmlspecialchars($nome); ?>" placeholder="Ex: José Nascimento da Silva">
                <label class="textotitulo" for="nome">Nome Completo</label>
            </div>

            <div class="container-input">
            <input type="text" name="data_nascimento" id="data_nascimento" value="<?php echo htmlspecialchars($data_nascimento); ?>" placeholder="Ex: 1996-06-21">
            <label class="textotitulo" for="data_nascimento">Data de nascimento</label>
            </div>

            <div class="container-input">
                <input type="text" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="Ex: josenacismento@gmail.com">
                <label class="textotitulo" for="email">E-mail</label>
            </div>

            <div class="container-input">
                <input type="text" name="profissao" value="<?php echo $profissao; ?>" placeholder="Ex: UX Designer">
                <label class="textotitulo" for="profissao">Profissão</label>
            </div>

            <div class="container-input">
                <input type="text" name="telefone_contato" id="telefone_contato" value="<?php echo $telefone_contato; ?>" placeholder="Ex: (11) 4033-2019">
                <label class="textotitulo" for="telefone_contato">Telefone para Contato</label>
            </div>

            <div class="container-input">
                <input type="text" name="celular_contato" id="celular_contato" value="<?php echo htmlspecialchars($celular_contato); ?>" placeholder="Ex: (11) 98493-2039">
                <label class="textotitulo" for="celular_contato">Celular para contato</label>
            </div>

            <div>
                <label class="caixa-assinalar">
                    <input type="checkbox" name="numero_whatsapp" <?php if ($numero_whatsapp) echo 'checked'; ?>>
                    Número de celular possui Whatsapp
                    <span class="check"></span>
                </label>
            </div>
            
            <div>
                <label class="caixa-assinalar">
                    <input type="checkbox" name="notificacao_email" <?php if ($notificacao_email) echo 'checked'; ?>>
                    Enviar Notificações por E-mail
                    <span class="check"></span>
                </label>
            </div>

            <div>
                <label class="caixa-assinalar">
                    <input type="checkbox" name="notificacao_sms" <?php if ($notificacao_sms) echo 'checked'; ?>>
                    Enviar Notificações por SMS
                    <span class="check"></span>
                </label>
            </div>



            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="submit" name="update" value="Salvar">
            <a href="formulario.php" class="botao-cancelar">Cancelar</a>

        </div>
    </form>

    <footer>
        <img src="assets/logo_rodape_alphacode.png" alt="" class="mainimg">
        <p>Termos | Política</p>
        <p>© Copyright 2022 | Desenvolvido por</p>
        <p>© Alphacode IT Solutions 2022</p>
    </footer>
</body>

<script type="text/javascript" src="Js/jquery-3.7.1.min.js"></script>
<script type="text/javascript" src="Js/jquery.mask.min.js"></script>

<script>
    $(document).ready(function() {
        $("#data_nascimento").mask("00/00/0000")
        $("#telefone_contato").mask("(00) 0000-0000")
        $("#celular_contato").mask("(00) 00000-0000")
    })
</script>

</html>