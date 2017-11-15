# MeuPPT Funções de Segurança e Otimização

Plugin com funções de segurança e otimização para clientes MeuPPT, porém aberto para qualquer um.
 
## Descrição
 
Rotinas e scripts de segurança geralmente incluídos no functions.php, mais melhorias para sites MeuPPT e de associados. O plugin é plug and play - não há necessidade de qualquer tipo de configuração. Entendemos que clientes em geral não possuem domínio de Wordpress suficiente para configurarem sozinhos mecanismos de proteção a exploit e funcionalidades dentro do ambiente do Wordpress. Por essa razão, a maioria das funções aqui implementadas, que visam segurança, otimização e facilidade, entram em ação assim que o plugin é ativado - deixando de operar quando o mesmo for desativado.
 
## Instalação
 
1. Carregue o ZIP no diretório `/wp-content/plugins/`
2. Active o plugin na seção 'Plugins' do WordPress
3. Todos os comandos e funções não precisam de configuração
 
## Frequently Asked Questions
 
#### Por que sem configurações?
 
Trata-se de um plugin para clientes, então funções já vêm configuradas.
 
## Changelog 

#### 1.4.9
* Carregamento assíncrono de scripts JS

#### 1.4.8
* Fix GZIP e hooks para inicialização da extensão zlib

#### 1.4.7
* Força processamento de CSS e JS em GZIP via classe safe-gzip.php

#### 1.4.6
* Instruções de segurança contra clickjacking e vulnerabilidades XSS

#### 1.4.5.4
* Fix do bloqueio ao carregamento de script de detecção de emojis - economia adicional de 2KB na abertura de página

#### 1.4.5.2
* Versões anteriores do HTACCESS são salvas em backup em pasta interna do plugin

#### 1.4.5.1
* Fixes e correções menores

#### 1.4.5
* Versão estável de aprimoramento do .htaccess
* Restrição de acesso ao .htaccess via Apache
* Ajuste de cache expiration e compressão
* Desabilita assinaturas no servidor e browsing de diretórios

#### 1.4.2
* Otimização de .htaccess Beta

#### 1.4.1
* User agent blacklist update

#### 1.4.0
* Firewall "express". Bloqueio de requisições maliciosas mais comuns e tentativas de acesso com user agents maliciosos

#### 1.3.2
* Desabilita pingbacks

#### 1.3.1
* Fix Safe SVG em seus diretórios e carregamento
* Remove WLW Manifest do cabeçalho

#### 1.3.0
* Incorporação da classe Safe SVG, para upload e manipulação segura de imagens SVG no Wordpress, com sanitização

#### 1.2.1
* Fix sistema de updater e atualização

#### 1.2.0
* Desabilita o método XML-RPC
* Remove RSS feed para comentários
* Desabilita emojis de um modo geral
* Adiciona botões de cor de fundo nas fontes, caracteres especiais
 
#### 1.0.0
* Impede exibição de versão do WP em META e em RSS
* Altera mensagens de erro no login que possam fornecer dicas a hackers para uma única mensagem sem especificações

## Próximas atualizações

* Considera como spam qualquer comentário com link que exceda 45 caracteres
* Carregamento asíncrono de scripts JS
* Bloqueio de requisições e user agents maliciosos mais comuns
* Redução do HTML na renderização - eliminação de comentários e espaços
