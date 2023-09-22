<?php

$nome = '';
$data_nascimento = '';
$email = '';
$celular_contato = '';

if (isset($_POST['submit'])) {

    $conexao = new PDO("mysql:host=localhost;dbname=db_mvc", "root", "");

    $nome = $_POST['nome'];
    $data_nascimento = $_POST['data_nascimento'];
        $result=explode('/',$data_nascimento);
        $dia = $result[0];
        $mes = $result[1];
        $ano = $result[2];
    $data_nascimento = $ano.'-'.$mes.'-'.$dia;    
    $email = $_POST['email'];
    $profissao = $_POST['profissao'];
    $telefone_contato = preg_replace('/[^0-9+]/', '', $_POST['telefone_contato']);
    $celular_contato = preg_replace('/[^0-9+]/', '', $_POST['celular_contato']);

    $numero_whatsapp = isset($_POST['numero_whatsapp']) ? 1 : 0;
    $notificacao_email = isset($_POST['notificacao_email']) ? 1 : 0;
    $notificacao_sms = isset($_POST['notificacao_sms']) ? 1 : 0;

    $sql = "INSERT INTO pessoa (nome, data_nascimento, email, profissao, telefone_contato, celular_contato, numero_whatsapp, notificacao_email, notificacao_sms) VALUES (:nome, :data_nascimento, :email, :profissao, :telefone_contato, :celular_contato, :numero_whatsapp, :notificacao_email, :notificacao_sms)";

    $stmt = $conexao->prepare($sql);

    $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
    $stmt->bindParam(':data_nascimento', $data_nascimento, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':profissao', $profissao, PDO::PARAM_STR);
    $stmt->bindParam(':telefone_contato', $telefone_contato, PDO::PARAM_STR);
    $stmt->bindParam(':celular_contato', $celular_contato, PDO::PARAM_STR);
    $stmt->bindParam(':numero_whatsapp', $numero_whatsapp, PDO::PARAM_INT);
    $stmt->bindParam(':notificacao_email', $notificacao_email, PDO::PARAM_INT);
    $stmt->bindParam(':notificacao_sms', $notificacao_sms, PDO::PARAM_INT);

    if ($stmt->execute()) {
        
        header('Location: formulario.php');
        exit;
    }

    $nome = '';
    $data_nascimento = '';
    $email = '';
    $celular_contato = '';
}

include_once('config.php');

$sql = 'SELECT * FROM pessoa';

$result = $pdo->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">

    <title>Document</title>
</head>

<body>
    <header>
        <img src="assets/logo_alphacode.png" alt="">
        <h1 id="titulo">Cadastro de contatos</h1>
    </header>

    <form action="formulario.php" method="POST">
        <div id="container">
            <div class="container-input">

                <input type="text" name="nome" value="<?php echo $nome ?>" placeholder="Ex: José Nascimento da Silva">
                <label class="textotitulo" for="nome">Nome Completo</label>

            </div>

            <div class="container-input">

                <input type="text" name="data_nascimento" id="data_nascimento" value="<?php echo htmlspecialchars($data_nascimento); ?>" placeholder="Ex: 21/06/1996">
                <label class="textotitulo" for="data_nascimento">Data de nascimento</label>

            </div>

            <div class="container-input">

                <input type="text" name="email" value="<?php echo $email ?>" placeholder="Ex: josenacismento@gmail.com">
                <label class="textotitulo" for="email">E-mail</label>

            </div>

            <div class="container-input">

                <input type="text" name="profissao" placeholder="Ex: UX Designer">
                <label class="textotitulo" for="profissao">Profissão</label>

            </div>

            <div class="container-input">

                <input type="text" name="telefone_contato" id="telefone_contato" placeholder="Ex: (11) 4033-2019">
                <label class="textotitulo" for="telefone_contato">Telefone para contato</label>

            </div>



            <div class="container-input">

                <input type="text" name="celular_contato" id="celular_contato" value="<?php echo $celular_contato ?>" placeholder="Ex: (11) 98493-2039">
                <label class="textotitulo" for="celular_contato">Celular para contato</label>

            </div>

            <div>

                <label class="caixa-assinalar">

                    <input type="checkbox" name="numero_whatsapp">
                    Número de celular possui Whatsapp

                    <span class="check"></span>

            </div>


            <div>

                <label class="caixa-assinalar">

                    <input type="checkbox" name="notificacao_email">
                    Enviar notificações por E-mail

                    <span class="check"></span>


            </div>

            <div>

                <label class="caixa-assinalar">

                    <input type="checkbox" name="notificacao_sms">
                    Enviar notificações por SMS

                    <span class="check"></span>

            </div>

            <input type="submit" name="submit" value="Cadastrar contato">

        </div>


        <hr>

        <div>
            <table>

                <thead>

                    <tr>

                        <th scope="col">Nome</th>
                        <th scope="col">Data de nascimento</th>
                        <th scope="col">E-mail</th>
                        <th scope="col">Celular para contato</th>
                        <th scope="col">Ação</th>

                    </tr>

                </thead>

                <tbody>
                    <?php

                    foreach ($result as $user_data) {
                        echo "<tr>";
                        echo "<td>" . $user_data['nome'] . "</td>";
                        echo "<td>" . date('d/m/Y', strtotime($user_data['data_nascimento'])) . "</td>";
                        echo "<td>" . $user_data['email'] . "</td>";
                        $celular = $user_data['celular_contato'];
                        $celular_formatado = '(' . substr($celular, 0, 2) . ') ' . substr($celular, 2, 5) . '-' . substr($celular, 7);
                        echo "<td>" . $celular_formatado . "</td>";
                        echo "<td> 

                                <a href='edit.php?id=$user_data[id]'><img src='assets/editar.png'></a>
                                <a href='delete.php?id=$user_data[id]' onclick='return confirmDelete();'><img src='assets/excluir.png'></a>
                            
                            </td>";

                        echo "<tr>";
                    }

                    ?>
                </tbody>

            </table>
        </div>
    </form>

    <footer>


        <img src="assets/logo_rodape_alphacode.png" alt="" class="mainimg">
        <p>Termos | Política</p>
        <p>© Copyright 2022 | Desenvolvido por</p>
        <p>©Alphacode IT Solutions 2022</p>

    </footer>
</body>

<script type="text/javascript" src="Js/jquery-3.7.1.min.js"></script>
<script type="text/javascript" src="Js/jquery.mask.min.js"></script>
<script>
    function confirmDelete() {
        return confirm("Tem certeza que deseja excluir essa informação de contato?");
    }
</script>
<script>
    $(document).ready(function() {
        $("#data_nascimento").mask("00/00/0000")
        $("#telefone_contato").mask("(00) 0000-0000")
        $("#celular_contato").mask("(00) 00000-0000")
    })
</script>

</html>