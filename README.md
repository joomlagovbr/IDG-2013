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

## Utilizando Docker

> **ATENÇÃO:** Essa abordagem tem a finalidade de demonstração e desenvolvimento. Os arquivos disponibilizados são exemplos, avalie segurança, melhores práticas e configurações específicas sempre que forem ser utilizados em produção.

É possível utilizar Docker para servir o portal, para isso foram incluídos exemplos de configuração.

### Preparando o ambiente

Instale as ferramentas necessárias:

- [Docker](https://docs.docker.com/get-docker/)
- [Docker Compose](https://docs.docker.com/compose/install/).

Para Windows 10 versão 2004 ou superior e Windows 11

- [WSL](https://docs.microsoft.com/pt-br/windows/wsl/install)

### Arquivos de configuração

Crie um arquivo .env na raiz do projeto e informe as variáveis de ambiente para que os serviços sejam ativados corretamente. São elas:

- JOOMLA_DB_HOST: Host da base de dados. Pode ser o serviço `db` disponível no docker-compose ou outro host. **Requerido**
- JOOMLA_DB_USER: Usuário para acesso à base de dados. **Requerido**
- JOOMLA_DB_PASSWORD: Senha de acesso à base de dados. Sem esta senha não será possível acessar o serviço. **Requerido**
- JOOMLA_DB_NAME: Nome da base de dados. Usado apenas na publicação automática de exemplo.
- JOOMLA_DB_PREFIX: Prefixo de tabela na base de dados. Usado apenas na publicação automática de exemplo.
- JOOMLA_ROOT_USERNAME: Super usuário do Joomla. Usado apenas na publicação automática de exemplo.
- JOOMLA_ROOT_PASSWORD: Senha do super usuário do Joomla. Usado apenas na publicação automática de exemplo.

Veja um exemplo:

```env
JOOMLA_DB_HOST=db
JOOMLA_DB_USER=root
JOOMLA_DB_PASSWORD=brasil
JOOMLA_DB_NAME=joomlagovdb
JOOMLA_DB_PREFIX=xmx0n_
JOOMLA_ROOT_USERNAME=joomlagov
JOOMLA_ROOT_PASSWORD=brasil
```

### Como rodar?

Utiliza-se o `docker-compose` para orquestrar os serviços da aplicação.

Existem dois arquivos de exemplos de configuração:

- docker-compose.yml: Utilize este arquivo para instalação de um portal padrão Joomla, com a configuração inicial sendo realizada pela plataforma.
- docker-compose.dev.yml: Utilize este arquivo apenas em localhost, para desenvolvimento. Ele configura um portal com dados preexistentes.

```bash
docker-compose up --build -d
```

Para servir localmente, acrescente `-f docker-compose.dev.yml` ao comando como em:

```bash
docker-compose -f docker-compose.dev.yml up --build -d
```

Se você estiver usando um sistema operacional Unix-like (Linux, Mac OS, WSL), talvez você precise ajustar as permissões dos arquivos. Para tanto, siga a orientação a seguir:

Por padrão, o id do usuário dentro da imagem **Docker** é definido para `1000`. Você pode alterar esse comportamento através de argumentos de build definidos no arquivo de configuração do **docker-composer**. Para isso, abra o terminal e identifique o seu `id` de usuário com o comando `id -u`. Em seguida, edite ou crie um arquivo de configuração do docker-compose conforme o exemplo abaixo:

```yaml
version: '3.9'

services:
  app:
    container_name: joomlagov_app
    build:
      context: .
      dockerfile: ./.docker/php/Dockerfile
      args:
        UID: 1000
   ...
```

### Serviços disponíveis

- gulp: task runner - acesse com <http://localhost:3000> - disponível apenas em ambiente de desenvolvimento
- app: página web - acesse com <http://localhost>
- db: servidor de banco de dados - não utilize dessa forma em produção, há sérios riscos de perda dados
- phpmyadmin: gerenciador do banco de dados - acesse com <http://localhost:8080>

## Documentação

Confira a documentação disponível até o momento no repositório provisório [https://github.com/joomlagovbr/documentacao](https://github.com/joomlagovbr/documentacao).

### Links diretos

- [Manual Template Portal Padrão, vr. 0.1](https://github.com/joomlagovbr/documentacao/raw/master/pdf/1.%20Manual%20Portal%20Padrao.pdf) - (as imagens são baseadas na versão Joomla 2.5, mas os princípios de alteração são os mesmos para a versão 3.3)
- [Como criar uma manchete de página inicial ou editoria](https://github.com/joomlagovbr/documentacao/raw/master/pdf/2.%20Pagina%20Inicial%20-%20Criar%20manchete.pdf)
- [Posições de template do projeto Portal Padrão (importante)](https://github.com/joomlagovbr/documentacao/raw/master/pdf/3.%20Posicoes%20de%20template%20do%20projeto%20portal%20padrao.pdf) - (as posições são geradas dinamicamente, através de uma lógica própria)

## Desenvolvimento

- [Comunidade Joomla Calango](http://www.joomlacalango.org/)
