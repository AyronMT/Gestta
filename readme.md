# 🚀 GestorOS - Sistema de Ordem de Serviço e Estoque

![Status do Projeto](https://img.shields.io/badge/status-concluído-green)
![Licença](https://img.shields.io/badge/license-MIT-blue.svg)

Sistema web para gerenciamento de ordens de serviço e controle de estoque, desenvolvido como Trabalho de Conclusão de Curso (TCC) do curso de Desenvolvimento de Sistemas. A solução é voltada para micro e pequenas empresas que buscam otimizar e digitalizar seus processos.

---

## 📋 Índice

* [Sobre o Projeto](#-sobre-o-projeto)
* [✨ Funcionalidades](#-funcionalidades)
* [📸 Screenshots](#-screenshots)
* [🛠️ Tecnologias Utilizadas](#️-tecnologias-utilizadas)
* [⚙️ Como Executar o Projeto](#️-como-executar-o-projeto)
* [👨‍💻 Autores](#-autores)
* [📄 Licença](#-licença)

---

## 🎯 Sobre o Projeto

Muitas microempresas de prestação de serviços ainda gerenciam suas operações com planilhas, papéis ou métodos informais. Isso gera retrabalho, perda de informações e falta de controle sobre o estoque de peças, impactando diretamente a eficiência e a lucratividade.

O **GestorOS** foi criado para resolver esse problema, oferecendo uma plataforma centralizada, intuitiva e de baixo custo, onde o pequeno empresário pode:
* Registrar e acompanhar o ciclo de vida de cada ordem de serviço.
* Manter o controle preciso do estoque de produtos e peças.
* Gerenciar sua base de clientes e técnicos.
* Obter uma visão clara e organizada do fluxo de trabalho.

---

## ✨ Funcionalidades

### 📦 Controle de Estoque
-   [✔️] Cadastro de produtos/peças com descrição, quantidade e preço.
-   [✔️] Movimentação de entrada e saída de itens.
-   [✔️] Baixa automática de estoque ao vincular um item a uma Ordem de Serviço.
-   [✔️] Alertas visuais para estoque baixo.

### 🔧 Ordens de Serviço (O.S.)
-   [✔️] Criação de O.S. vinculada a um cliente e técnico.
-   [✔️] Descrição detalhada do equipamento e do defeito relatado.
-   [✔️] Acompanhamento por status (Ex: `Aberto`, `Em Análise`, `Aguardando Peça`, `Concluído`).
-   [✔️] Adição de produtos/peças do estoque diretamente na O.S.
-   [✔️] Geração de O.S. em formato PDF para impressão ou envio.

### 👤 Cadastros Gerais
-   [✔️] Gestão de Clientes (CRUD - Criar, Ler, Atualizar, Deletar).
-   [✔️] Gestão de Técnicos/Funcionários (CRUD).
-   [✔️] Sistema de autenticação e controle de acesso de usuários.

---

## 📸 Screenshots

<p align="center">
  <em>(Substitua os links abaixo pelos links das imagens do seu projeto. Você pode subir as imagens no próprio GitHub)</em>
</p>
<p align="center">
  <img src="[LINK_DA_IMAGEM_TELA_LOGIN]" alt="Tela de Login" width="48%">
  <img src="[LINK_DA_IMAGEM_DASHBOARD]" alt="Dashboard Principal" width="48%">
</p>
<p align="center">
  <img src="[LINK_DA_IMAGEM_CADASTRO_OS]" alt="Cadastro de Ordem de Serviço" width="48%">
  <img src="[LINK_DA_IMAGEM_CONTROLE_ESTOQUE]" alt="Tela de Controle de Estoque" width="48%">
</p>

---

## 🛠️ Tecnologias Utilizadas

Este projeto foi desenvolvido com as seguintes tecnologias:

| Ferramenta | Descrição |
|------------|-------------|
| **Frontend** | `[Ex: React.js, Vue.js, Angular, HTML5, CSS3]` |
| **Backend** | `[Ex: Node.js com Express, Python com Django, PHP com Laravel]` |
| **Banco de Dados** | `[Ex: MySQL, PostgreSQL, SQLite, MongoDB]` |
| **Hospedagem** | `[Ex: Vercel (Frontend), Heroku (Backend), AWS]` |
| **Ferramentas** | `Git, VS Code, Insomnia, Figma` |

---

## ⚙️ Como Executar o Projeto

Siga as instruções abaixo para configurar e executar o projeto em seu ambiente local.

### Pré-requisitos
* [Node.js](https://nodejs.org/en/) (versão `[Ex: 18.x ou superior]`)
* [Git](https://git-scm.com/)
* Um gerenciador de pacotes ([NPM](https://www.npmjs.com/) ou [Yarn](https://yarnpkg.com/))
* Banco de Dados `[Ex: MySQL]` instalado e rodando.

### Passo a Passo

```bash
# 1. Clone o repositório para sua máquina
git clone [https://github.com/](https://github.com/)[SEU_USUARIO]/[NOME_DO_REPOSITORIO].git

# 2. Acesse a pasta do projeto
cd [NOME_DO_REPOSITORIO]

# 3. Instale as dependências do Backend
cd backend
npm install

# 4. Instale as dependências do Frontend
cd ../frontend
npm install

# 5. Configure as variáveis de ambiente no backend
#    - Renomeie o arquivo '.env.example' para '.env' na pasta 'backend'
#    - Preencha com suas credenciais do banco de dados (host, usuário, senha, etc.)

# 6. Rode as migrações para criar as tabelas no banco de dados (se aplicável)
cd ../backend
npx [comando-da-migracao] # Ex: npx sequelize-cli db:migrate

# 7. Inicie o servidor backend
npm start

# 8. Inicie a aplicação frontend (em outro terminal)
cd ../frontend
npm start

# A aplicação estará rodando em http://localhost:[PORTA_DO_FRONTEND]
```

---

## 👨‍💻 Autores

Este projeto foi desenvolvido por:

* **[Seu Nome Completo]** - [LinkedIn](https://www.linkedin.com/in/[SEU_LINKEDIN]/) | [GitHub](https://github.com/[SEU_USUARIO])
* **[Nome do Colega, se houver]** - [LinkedIn](https://www.linkedin.com/in/[LINKEDIN_COLEGA]/) | [GitHub](https://github.com/[USUARIO_COLEGA])

---

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.
