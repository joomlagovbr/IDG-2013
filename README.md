Portal padrão em CMS Joomla 3.7.4
================

Sobre esta versão
---------------------
O Joomla 3.7.4 é recomendado para versões do PHP 5.3.10+. Compátivel com PHP 7+ 

Acompanhe as atualizações do projeto
---------------------
Participe do grupo do google chamado [Joomla! e-Gov](https://groups.google.com/forum/?hl=pt-BR#!forum/joomla-e-gov-br) para se manter informado sobre o Portal Padrão em CMS Joomla. As atualizações não possuem data programada e ocorrem de acordo com a disponibilidade dos voluntários participantes.


ATENÇÃO
---------------------
Este projeto visa ser um *quickstart* para iniciar projetos próprios de portais padrão em CMS Joomla, e atende as principais especificações e módulos recomendados pela Presidência da República, mas não esgota todos os módulos e recomendações citadas nos manuais.

Os voluntários deste grupo não se responsabilizam pela incorreta utilização deste pacote, bem como pela incorreta configuração do servidor de produção, no que se refere
a quesitos segurança e performance.

Recomenda-se a utilização de ambiente LAMP (Linux, Apache, MySQL, PHP), configurado para ambientes de produção de governo, o que implica configurações severas de permissões de pasta, restrições de acesso ao diretório /administrator, realização de BACKUPS, dentre outras boas práticas.

ESTE PROJETO É RECOMENDADO PARA PROFISSIONAIS COM EXPERIÊNCIA NA UTILIZAÇÃO DO CMS JOOMLA.


Configuração inicial
---------------------
Não é mais necessário alterar o arquivo configuration.php manualmente.

O instalador padrão do CMS Joomla! 3.x foi customizado para configurar uma instalação padrão do CMS Joomla! com componentes, módulos, template e conteúdo de exemplo do Portal Padrão.

Após concluir instalação e entrar no administrativo, rodar reparos no banco de dados.
Cole o link /administrator/index.php?option=com_installer&view=database, e execute o clicando no botão 'Corrigir'


Documentação
---------------------
Confira a documentação disponível até o momento no repositório provisório [https://github.com/joomlagovbr/documentacao](https://github.com/joomlagovbr/documentacao).

###Links diretos:
-   [Manual Template Portal Padrão, vr. 0.1](https://github.com/joomlagovbr/documentacao/raw/master/pdf/1.%20Manual%20Portal%20Padrao.pdf) - (as imagens são baseadas na versão Joomla 2.5, mas os princípios de alteração são os mesmos para a versão 3.3)
-   [Como criar uma manchete de página inicial ou editoria](https://github.com/joomlagovbr/documentacao/raw/master/pdf/2.%20Pagina%20Inicial%20-%20Criar%20manchete.pdf)
-   [Posições de template do projeto Portal Padrão (importante)](https://github.com/joomlagovbr/documentacao/raw/master/pdf/3.%20Posicoes%20de%20template%20do%20projeto%20portal%20padrao.pdf) - (as posições são geradas dinamicamente, através de uma lógica própria)


Desenvolvimento
---------------------
-   [Comunidade Joomla Calango](http://www.joomlacalango.org/)

Ultimas mudanças
--------------------
-Correções no CSS referentes aos modulos, menus e banners
-Correções de nas funcionalidades da busca padrao, somente no Override de template."original nao afetado"
-Adição de novo Plugin de busca de busca por Menu(Descobrir item no gerenciador). Sera incorporado posteriormente.
Opcional( se colocado  uma palavra chave no plugin e nos itens de menu, terão prioridade na busca.
-Update AllVideos 4.8.0
-Update K2 2.8.0
-Update Phoca Gallery 4.3.6
-Update Youtube Gallery 4.4.0
