Portal padrão em CMS Joomla 3.2
================

Acompanhe as atualizações do projeto
---------------------
Participe do grupo do google chamado [Joomla! e-Gov](https://groups.google.com/forum/?hl=pt-BR#!forum/joomla-e-gov-br) para se manter informado sobre o Portal Padrão em CMS Joomla. As atualizações não possuem data programada e ocorrem de acordo com a disponibilidade dos voluntários participantes.


ATENÇÃO
---------------------
Os voluntários deste grupo não se responsabilizam pela incorreta utilização deste pacote, bem como pela incorreta configuração do servidor de produção, no que se refere
aos quesitos segurança e performance.

Recomenda-se a utilização de ambiente LAMP (Linux, Apache, MySQL, PHP), configurado para ambientes de produção de governo, o que implica configurações severas de permissões de pasta, restrições de acesso ao diretório /administrator, realização de BACKUPS, dentre outras boas práticas.

ESTE PROJETO É RECOMENDADO PARA PROFISSIONAIS COM EXPERIÊNCIA NA UTILIZAÇÃO DO CMS JOOMLA.


Configuração inicial
---------------------
Altere os dados de conexão com o banco no arquivo configuration.php
É necessário informar usuário e senha de banco de dados, no mínimo.
Ao executar o script de banco, um novo schema MySQL chamado portal_padrao_3x será criado.

###Dados de acesso à área administrativa:
-   user: admin
-   senha: admin

ALTERE OS DADOS DE ACESSO NOS AMBIENTES DE PRODUÇÃO E HOMOLOGAÇÃO.


Documentação
---------------------
Confira a documentação disponível até o momento no repositório provisório [https://github.com/joomlagovbr/documentacao](https://github.com/joomlagovbr/documentacao).

###Links diretos:
-   [Manual Template Portal Padrão, vr. 0.1](https://github.com/joomlagovbr/documentacao/raw/master/pdf/1.%20Manual%20Portal%20Padrao.pdf) - (as imagens são baseadas na versão Joomla 2.5, mas os princípios de alteração são os mesmos para a versão 3.2)
-   [Como criar uma manchete de página inicial ou editoria](https://github.com/joomlagovbr/documentacao/raw/master/pdf/2.%20Pagina%20Inicial%20-%20Criar%20manchete.pdf)
-   [Posições de template do projeto Portal Padrão (importante)](https://github.com/joomlagovbr/documentacao/raw/master/pdf/3.%20Posicoes%20de%20template%20do%20projeto%20portal%20padrao.pdf) - (as posições são geradas dinamicamente, através de uma lógica própria)


Desenvolvimento
---------------------
-   [Comunidade Joomla Calango](http://www.joomlacalango.org/)