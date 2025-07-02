Requisitos de Instalação do Módulo Gestão FinanceiraEste módulo utiliza bibliotecas de terceiros que são geridas através do Composer, o gestor de pacotes para PHP. Para que todas as funcionalidades, como a importação e exportação de planilhas Excel, funcionem corretamente, é necessário instalar estas dependências.Dependênciasphpoffice/phpspreadsheet: Biblioteca para ler e escrever ficheiros de planilha (Excel, CSV).Como Instalar as DependênciasAs instruções abaixo devem ser executadas através da linha de comandos, diretamente no servidor ou no contentor Docker onde a sua aplicação Perfex CRM está a correr.1. Aceder ao Terminal do Servidor/ContentorSe estiver a usar Docker, primeiro precisa de aceder ao terminal do seu contentor.# Encontre o nome ou ID do seu contentor
docker ps

# Aceda ao terminal do contentor (substitua <nome_do_contentor>)
docker exec -it <nome_do_contentor> /bin/bash
2. Navegar para a Pasta do MóduloUma vez no terminal, navegue para a pasta raiz do módulo gestaofinanceira.cd application/modules/gestaofinanceira/
3. Instalar o Composer (se necessário)Execute o comando composer. Se receber um erro como "command not found", significa que o Composer não está instalado. Instale-o com os seguintes comandos:# Descarrega o instalador
php -r "copy('[https://getcomposer.org/installer](https://getcomposer.org/installer)', 'composer-setup.php');"

# Executa a instalação
php composer-setup.php

# Apaga o instalador
php -r "unlink('composer-setup.php');"

# Move o Composer para que seja acessível globalmente
mv composer.phar /usr/local/bin/composer
4. Instalar a Biblioteca PhpSpreadsheetAgora que o Composer está instalado e você está na pasta do módulo, execute o seguinte comando:composer require phpoffice/phpspreadsheet
Este comando irá criar uma pasta vendor/ dentro do seu módulo, contendo a biblioteca `PhpSpreadsheet