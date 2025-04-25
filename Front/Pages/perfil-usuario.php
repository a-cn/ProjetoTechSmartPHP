<?php
require_once '../../Back/verifica_sessao.php'; //Garante que somente usuários logados possam acessar a página
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Conta</title>
    <link rel="stylesheet" type="text/css" href="../CSS/perfil-usuario.css">
</head>
<body>
<?php include 'sidebar-header.php'; ?> <!-- Inclui o cabeçalho e a barra de navegação -->

    <div class="profile-container">
        <div class="profile-header">
            <h1>Minha Conta</h1>
        </div>
        
        <div class="profile-body">
            <!-- Informações Pessoais -->
            <div class="info-section">
                <h3 class="section-title">Informações Pessoais</h3>
                
                <div class="info-row">
                    <div class="info-label">Nome Completo</div>
                    <div class="info-value" id="nome"></div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Tipo de Usuário</div>
                    <div class="info-value" id="tipo">
                        <span class="badge badge-primary"></span>
                    </div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">CPF/CNPJ</div>
                    <div class="info-value" id="cpf-cnpj"></div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Data de Nascimento</div>
                    <div class="info-value" id="nascimento"></div>
                </div>
            </div>
            
            <!-- Contato -->
            <div class="info-section">
                <h3 class="section-title">Informações de Contato</h3>
                
                <div class="info-row">
                    <div class="info-label">E-mail</div>
                    <div class="info-value" id="email"></div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Telefone Principal</div>
                    <div class="info-value" id="num-principal"></div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Telefone Recado</div>
                    <div class="info-value" id="num-recado"></div>
                </div>
            </div>
            
            <!-- Endereço -->
            <div class="info-section">
                <h3 class="section-title">Endereço</h3>
                
                <div class="info-row">
                    <div class="info-label">CEP</div>
                    <div class="info-value" id="cep"></div>
                </div>

                <div class="info-row">
                    <div class="info-label">Cidade/Estado</div>
                    <div class="info-value" id="cidade-estado"></div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Logradouro</div>
                    <div class="info-value" id="logradouro"></div>
                </div>

                <div class="info-row">
                    <div class="info-label">Número</div>
                    <div class="info-value" id="numero"></div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Bairro</div>
                    <div class="info-value" id="bairro"></div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Complemento</div>
                    <div class="info-value" id="complemento"></div>
                </div>
            </div>
            
            <div class="last-access">
                
            </div>
        </div>

        <div class="profile-actions">
            <a href="editar-usuario.php" class="btn-editar">Alterar Dados</a>
            <form method="POST" action="../../Back/desativar_conta.php" style="display:inline;">
                <button type="submit" class="btn-desativar" onclick="return confirm('Tem certeza que deseja desativar sua conta?')">Desativar Conta</button>
            </form>
        </div>

    </div>
    <script src="../JavaScript/perfil-usuario.js"></script>
</body>
</html>