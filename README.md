# Portal padrão em CMS Joomla 3.9.6

Se tiverem dificuldades, podem entrar em contato: tiagovtg@gmail.com

## Sobre esta versão

O Joomla 3.9.6 é recomendado para versões do PHP 5.3.10+. Melhor usando PHP 7.2.+

_Atualização somente do Tema_ pode ser feito separadamente por esse outro [repositório do Bernado](https://github.com/dioubernardo/pip-joomla).

### Acompanhe as atualizações do projeto

Participe do grupo do google chamado [Joomla! e-Gov](https://groups.google.com/forum/?hl=pt-BR#!forum/joomla-e-gov-br) para se manter informado sobre o Portal Padrão em CMS Joomla. As atualizações não possuem data programada e ocorrem de acordo com a disponibilidade dos voluntários participantes.

### Atenção

Este projeto visa ser um _quickstart_ para iniciar projetos próprios de portais padrão em CMS Joomla, e atende as principais especificações e módulos recomendados pela Presidência da República, mas não esgota todos os módulos e recomendações citadas nos manuais.

Os voluntários deste grupo não se responsabilizam pela incorreta utilização deste pacote, bem como pela incorreta configuração do servidor de produção, no que se refere
a quesitos segurança e performance.

Recomenda-se a utilização de ambiente LAMP (Linux, Apache, MySQL, PHP), configurado para ambientes de produção de governo, o que implica configurações severas de permissões de pasta, restrições de acesso ao diretório /administrator, realização de BACKUPS, dentre outras boas práticas.

ESTE PROJETO É RECOMENDADO PARA PROFISSIONAIS COM EXPERIÊNCIA NA UTILIZAÇÃO DO CMS JOOMLA.

## Configuração inicial

Não é mais necessário alterar o arquivo configuration.php manualmente.

O instalador padrão do CMS Joomla! 3.x foi customizado para configurar uma instalação padrão do CMS Joomla! com componentes, módulos, template e conteúdo de exemplo do Portal Padrão.

## Problemas na instalação
Se tiver problemas na instalação e travar no meio, tente alterar as variaveis de ambiente do PHP
Arquivo:
php.ini

Alterações:
max_execution_time=600
;(valor padrão 30, alterado para 600)

max_input_time=1200
;(valor padrão 60, alterado para 1200)

max_input_vars = 6000
;padrão linha comentada, descomentar esta linha
;(valor padrão 1000, alterado para 6000)

memory_limit=1280M
;(valor padrão 128M, alterado para 1280M)

Não precisa de aumentar tanto, mas pode ir testando se quiser, exemplo, memoria padrão é 128M, pode ir subindo 256M,512M, 1024M

Se tiverem dificuldades, podem entrar em contato: tiagovtg@gmail.com

## Documentação

Confira a documentação disponível até o momento no repositório provisório [https://github.com/joomlagovbr/documentacao](https://github.com/joomlagovbr/documentacao).

### Links diretos

- [Manual Template Portal Padrão, vr. 0.1](https://github.com/joomlagovbr/documentacao/raw/master/pdf/1.%20Manual%20Portal%20Padrao.pdf) - (as imagens são baseadas na versão Joomla 2.5, mas os princípios de alteração são os mesmos para a versão 3.3)
- [Como criar uma manchete de página inicial ou editoria](https://github.com/joomlagovbr/documentacao/raw/master/pdf/2.%20Pagina%20Inicial%20-%20Criar%20manchete.pdf)
- [Posições de template do projeto Portal Padrão (importante)](https://github.com/joomlagovbr/documentacao/raw/master/pdf/3.%20Posicoes%20de%20template%20do%20projeto%20portal%20padrao.pdf) - (as posições são geradas dinamicamente, através de uma lógica própria)

## Desenvolvimento

- [Comunidade Joomla Calango](http://www.joomlacalango.org/)
