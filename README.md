# MeuPPT Funções de Segurança e Otimização

Contributors: meuppt
Donate link: http://meuppt.pt/
Tags: security, safety, enhancement, meuppt, segurança, otimização
Requires at least: 3.0.1
Tested up to: 4.8
Requires PHP: 5.2.4
Stable tag: 4.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
 
Plugin com funções de segurança e otimização para clientes MeuPPT, porém aberto para qualquer um.
 
## Descrição
 
Rotinas e scripts de segurança geralmente incluídos no functions.php, mais melhorias para sites MeuPPT e de associados.
 
## Instalação
 
1. Carregue o ZIP no diretório `/wp-content/plugins/`
2. Active o plugin na seção 'Plugins' do WordPress
3. Todos os comandos e funções não precisam de configuração
 
## Frequently Asked Questions
 
#### Por que sem configurações?
 
Trata-se de um plugin para clientes, então funções já vêm configuradas.
 
## Changelog 

#### 1.3.1
* Fix Safe SVG em seus diretórios e carregamento

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

* Restrição de acesso ao painel do adminpor Subscribers e Contributors
* Liberação para uso de compressão de ficheiros via GZIP
* Considera como spam qualquer comentário com link que exceda 45 caracteres
