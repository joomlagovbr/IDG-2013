Portal padrão em CMS Joomla 3.2
================

ACOMPANHE AS ATUALIZAÇÕES
---------------------
Participe do grupo do google chamado [Joomla! e-Gov](https://groups.google.com/forum/?hl=pt-BR#!forum/joomla-e-gov-br) para se manter informado sobre o Portal Padrão em CMS Joomla. As atualizações não possuem data programada e ocorrem de acordo com a disponibilidade dos voluntários participantes.


AVISO IMPORTANTE
---------------------
Os voluntários deste grupo não se responsabilizam pela incorreta utilização deste pacote, bem como pela incorreta configuração do servidor de produção, no que se refere
aos quesitos segurança e performance.

Recomenda-se a utilização de ambiente LAMP (Linux, Apache, MySQL, PHP), configurado para ambientes de produção de governo, o que implica configurações severas de permissões de pasta, restrições de acesso ao diretório /administrator, realização de BACKUPS, dentre outras boas práticas.

ESTE PROJETO É RECOMENDADO PARA PROFISSIONAIS COM EXPERIÊNCIA NA UTILIZAÇÃO DO CMS JOOMLA.


CONFIGURAÇÃO
---------------------
Altere os dados de conexão com o banco no arquivo configuration.php
É necessário informar usuário e senha de banco de dados, no mínimo.
Ao executar o script de banco, um novo schema MySQL chamado portal_padrao_3x será criado.

###Dados de acesso à área administrativa:
-   user: admin
-   senha: admin

ALTERE OS DADOS DE ACESSO NOS AMBIENTES DE PRODUÇÃO E HOMOLOGAÇÃO.