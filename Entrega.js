const {
    Document, Packer, Paragraph, TextRun, Table, TableRow, TableCell,
    HeadingLevel, AlignmentType, BorderStyle, WidthType, ShadingType,
    LevelFormat, PageNumber, Header, Footer
  } = require('docx');
  const fs = require('fs');
  
  const border = { style: BorderStyle.SINGLE, size: 1, color: "CCCCCC" };
  const borders = { top: border, bottom: border, left: border, right: border };
  const headerBorder = { style: BorderStyle.SINGLE, size: 1, color: "C0185C" };
  const headerBorders = { top: headerBorder, bottom: headerBorder, left: headerBorder, right: headerBorder };
  
  function headerCell(text, width) {
    return new TableCell({
      borders: headerBorders,
      width: { size: width, type: WidthType.DXA },
      shading: { fill: "F9D0E0", type: ShadingType.CLEAR },
      margins: { top: 100, bottom: 100, left: 150, right: 150 },
      children: [new Paragraph({
        alignment: AlignmentType.CENTER,
        children: [new TextRun({ text, bold: true, size: 22, font: "Arial", color: "7B1034" })]
      })]
    });
  }
  
  function dataCell(text, width, bold = false) {
    return new TableCell({
      borders,
      width: { size: width, type: WidthType.DXA },
      margins: { top: 80, bottom: 80, left: 150, right: 150 },
      children: [new Paragraph({
        children: [new TextRun({ text, size: 20, font: "Arial", bold })]
      })]
    });
  }
  
  function sectionTitle(text) {
    return new Paragraph({
      spacing: { before: 300, after: 120 },
      children: [new TextRun({ text, bold: true, size: 26, font: "Arial", color: "C0185C" })]
    });
  }
  
  function subTitle(text) {
    return new Paragraph({
      spacing: { before: 200, after: 80 },
      children: [new TextRun({ text, bold: true, size: 22, font: "Arial", color: "2C2C2C" })]
    });
  }
  
  function bodyText(text) {
    return new Paragraph({
      spacing: { after: 80 },
      children: [new TextRun({ text, size: 20, font: "Arial" })]
    });
  }
  
  function bulletItem(text) {
    return new Paragraph({
      numbering: { reference: "bullets", level: 0 },
      children: [new TextRun({ text, size: 20, font: "Arial" })]
    });
  }
  
  // Requisitos Funcionais data
  const requisitos = [
    {
      id: "RF-01",
      modulo: "Autenticação",
      nome: "Login de Usuário",
      descricao: "O sistema deve permitir que usuários cadastrados realizem login com e-mail e senha.",
      ator: "Usuário",
      prioridade: "Alta",
      entradas: "E-mail, senha",
      saidas: "Token de sessão / Mensagem de erro",
      regras: "Credenciais devem ser validadas. Sessão expira em 8h."
    },
    {
      id: "RF-02",
      modulo: "Autenticação",
      nome: "Logout",
      descricao: "O sistema deve encerrar a sessão do usuário ao clicar em 'Sair'.",
      ator: "Usuário",
      prioridade: "Alta",
      entradas: "Ação do usuário",
      saidas: "Sessão encerrada, redirecionamento à tela de login",
      regras: "Token de sessão invalidado imediatamente."
    },
    {
      id: "RF-03",
      modulo: "Autenticação",
      nome: "Controle de Acesso",
      descricao: "O sistema deve restringir funcionalidades com base no perfil do usuário (Admin, Operador).",
      ator: "Sistema",
      prioridade: "Alta",
      entradas: "Perfil do usuário autenticado",
      saidas: "Menu e ações disponíveis conforme perfil",
      regras: "Admin acessa todas as funções; Operador acessa apenas consultas e pacotes."
    },
    {
      id: "RF-04",
      modulo: "Gestão de Pacotes",
      nome: "Cadastrar Pacote",
      descricao: "O sistema deve permitir o cadastro de novos pacotes com nome, código de rastreio, remetente, destinatário, preço, peso, quantidade e status.",
      ator: "Operador / Admin",
      prioridade: "Alta",
      entradas: "Nome, código de rastreio, remetente, destinatário, preço, peso, quantidade, status",
      saidas: "Pacote salvo; mensagem 'Pacote cadastrado com sucesso!'",
      regras: "Código de rastreio único. Peso mínimo 0,010 kg. Status padrão: Pendente."
    },
    {
      id: "RF-05",
      modulo: "Gestão de Pacotes",
      nome: "Listar Pacotes",
      descricao: "O sistema deve exibir a lista de pacotes cadastrados com código, nome, remetente, destinatário, peso, quantidade e status.",
      ator: "Operador / Admin",
      prioridade: "Alta",
      entradas: "Filtros opcionais (busca textual, status)",
      saidas: "Tabela de pacotes filtrada",
      regras: "Exibir total de pacotes encontrados."
    },
    {
      id: "RF-06",
      modulo: "Gestão de Pacotes",
      nome: "Editar Pacote",
      descricao: "O sistema deve permitir a edição de dados de um pacote existente.",
      ator: "Operador / Admin",
      prioridade: "Média",
      entradas: "Campos editáveis do pacote",
      saidas: "Dados atualizados; mensagem de sucesso",
      regras: "Código de rastreio não pode ser alterado para um já existente."
    },
    {
      id: "RF-07",
      modulo: "Gestão de Pacotes",
      nome: "Excluir Pacote",
      descricao: "O sistema deve permitir a exclusão de pacotes cadastrados.",
      ator: "Admin",
      prioridade: "Média",
      entradas: "ID do pacote",
      saidas: "Pacote removido; mensagem 'Pacote removido com sucesso!'",
      regras: "Apenas Admin pode excluir. Ação irreversível."
    },
    {
      id: "RF-08",
      modulo: "Gestão de Pacotes",
      nome: "Buscar e Filtrar Pacotes",
      descricao: "O sistema deve permitir busca por código, destinatário ou nome, e filtragem por status.",
      ator: "Operador / Admin",
      prioridade: "Média",
      entradas: "Texto de busca, status selecionado",
      saidas: "Lista filtrada de pacotes",
      regras: "Busca case-insensitive. Filtro status: Todos, Pendente, Em Rota, Entregue."
    },
    {
      id: "RF-09",
      modulo: "Gestão de Estoque",
      nome: "Controle de Quantidade",
      descricao: "O sistema deve rastrear a quantidade de itens em estoque para cada produto.",
      ator: "Sistema / Admin",
      prioridade: "Alta",
      entradas: "Movimentações de entrada e saída",
      saidas: "Quantidade atualizada por produto",
      regras: "Quantidade não pode ser negativa."
    },
    {
      id: "RF-10",
      modulo: "Alertas de Estoque",
      nome: "Alerta de Estoque Baixo",
      descricao: "O sistema deve emitir alertas quando a quantidade de um produto atingir o nível mínimo definido.",
      ator: "Sistema",
      prioridade: "Alta",
      entradas: "Quantidade atual, quantidade mínima configurada",
      saidas: "Alerta visual no dashboard e notificação ao usuário responsável",
      regras: "Alerta gerado quando quantidade atual <= estoque_minimo."
    },
    {
      id: "RF-11",
      modulo: "Alertas de Estoque",
      nome: "Configurar Nível Mínimo de Estoque",
      descricao: "O sistema deve permitir configurar a quantidade mínima de alerta para cada produto.",
      ator: "Admin",
      prioridade: "Média",
      entradas: "Produto, quantidade mínima",
      saidas: "Nível mínimo salvo",
      regras: "Valor deve ser maior que zero."
    },
    {
      id: "RF-12",
      modulo: "Dashboard",
      nome: "Visualizar Dashboard",
      descricao: "O sistema deve exibir um painel com indicadores gerais: total de pacotes, status por categoria e gráficos de desempenho.",
      ator: "Usuário",
      prioridade: "Média",
      entradas: "Dados consolidados do banco",
      saidas: "Gráficos e métricas em tempo real",
      regras: "Dados atualizados ao carregar a página."
    }
  ];
  
  // Build RF rows
  const rfRows = [
    new TableRow({
      children: [
        headerCell("ID", 900),
        headerCell("Módulo", 1400),
        headerCell("Nome", 1800),
        headerCell("Descrição", 2600),
        headerCell("Ator", 1100),
        headerCell("Prioridade", 960),
      ]
    }),
    ...requisitos.map(r => new TableRow({
      children: [
        dataCell(r.id, 900, true),
        dataCell(r.modulo, 1400),
        dataCell(r.nome, 1800),
        dataCell(r.descricao, 2600),
        dataCell(r.ator, 1100),
        dataCell(r.prioridade, 960, true),
      ]
    }))
  ];
  
  const rfTable = new Table({
    width: { size: 8760, type: WidthType.DXA },
    columnWidths: [900, 1400, 1800, 2600, 1100, 960],
    rows: rfRows
  });
  
  // Detail rows for each RF
  function rfDetailTable(r) {
    return new Table({
      width: { size: 8760, type: WidthType.DXA },
      columnWidths: [1800, 6960],
      rows: [
        new TableRow({ children: [
          new TableCell({ borders: headerBorders, width: { size: 8760, type: WidthType.DXA }, columnSpan: 2,
            shading: { fill: "F9D0E0", type: ShadingType.CLEAR },
            margins: { top: 80, bottom: 80, left: 150, right: 150 },
            children: [new Paragraph({ children: [new TextRun({ text: `${r.id} — ${r.nome}`, bold: true, size: 22, font: "Arial", color: "7B1034" })] })]
          })
        ]}),
        new TableRow({ children: [
          new TableCell({ borders, width: { size: 1800, type: WidthType.DXA }, shading: { fill: "FBF0F5", type: ShadingType.CLEAR }, margins: { top: 80, bottom: 80, left: 150, right: 150 }, children: [new Paragraph({ children: [new TextRun({ text: "Módulo", bold: true, size: 20, font: "Arial" })] })] }),
          new TableCell({ borders, width: { size: 6960, type: WidthType.DXA }, margins: { top: 80, bottom: 80, left: 150, right: 150 }, children: [new Paragraph({ children: [new TextRun({ text: r.modulo, size: 20, font: "Arial" })] })] }),
        ]}),
        new TableRow({ children: [
          new TableCell({ borders, width: { size: 1800, type: WidthType.DXA }, shading: { fill: "FBF0F5", type: ShadingType.CLEAR }, margins: { top: 80, bottom: 80, left: 150, right: 150 }, children: [new Paragraph({ children: [new TextRun({ text: "Descrição", bold: true, size: 20, font: "Arial" })] })] }),
          new TableCell({ borders, width: { size: 6960, type: WidthType.DXA }, margins: { top: 80, bottom: 80, left: 150, right: 150 }, children: [new Paragraph({ children: [new TextRun({ text: r.descricao, size: 20, font: "Arial" })] })] }),
        ]}),
        new TableRow({ children: [
          new TableCell({ borders, width: { size: 1800, type: WidthType.DXA }, shading: { fill: "FBF0F5", type: ShadingType.CLEAR }, margins: { top: 80, bottom: 80, left: 150, right: 150 }, children: [new Paragraph({ children: [new TextRun({ text: "Ator Principal", bold: true, size: 20, font: "Arial" })] })] }),
          new TableCell({ borders, width: { size: 6960, type: WidthType.DXA }, margins: { top: 80, bottom: 80, left: 150, right: 150 }, children: [new Paragraph({ children: [new TextRun({ text: r.ator, size: 20, font: "Arial" })] })] }),
        ]}),
        new TableRow({ children: [
          new TableCell({ borders, width: { size: 1800, type: WidthType.DXA }, shading: { fill: "FBF0F5", type: ShadingType.CLEAR }, margins: { top: 80, bottom: 80, left: 150, right: 150 }, children: [new Paragraph({ children: [new TextRun({ text: "Entradas", bold: true, size: 20, font: "Arial" })] })] }),
          new TableCell({ borders, width: { size: 6960, type: WidthType.DXA }, margins: { top: 80, bottom: 80, left: 150, right: 150 }, children: [new Paragraph({ children: [new TextRun({ text: r.entradas, size: 20, font: "Arial" })] })] }),
        ]}),
        new TableRow({ children: [
          new TableCell({ borders, width: { size: 1800, type: WidthType.DXA }, shading: { fill: "FBF0F5", type: ShadingType.CLEAR }, margins: { top: 80, bottom: 80, left: 150, right: 150 }, children: [new Paragraph({ children: [new TextRun({ text: "Saídas", bold: true, size: 20, font: "Arial" })] })] }),
          new TableCell({ borders, width: { size: 6960, type: WidthType.DXA }, margins: { top: 80, bottom: 80, left: 150, right: 150 }, children: [new Paragraph({ children: [new TextRun({ text: r.saidas, size: 20, font: "Arial" })] })] }),
        ]}),
        new TableRow({ children: [
          new TableCell({ borders, width: { size: 1800, type: WidthType.DXA }, shading: { fill: "FBF0F5", type: ShadingType.CLEAR }, margins: { top: 80, bottom: 80, left: 150, right: 150 }, children: [new Paragraph({ children: [new TextRun({ text: "Regras de Negócio", bold: true, size: 20, font: "Arial" })] })] }),
          new TableCell({ borders, width: { size: 6960, type: WidthType.DXA }, margins: { top: 80, bottom: 80, left: 150, right: 150 }, children: [new Paragraph({ children: [new TextRun({ text: r.regras, size: 20, font: "Arial" })] })] }),
        ]}),
        new TableRow({ children: [
          new TableCell({ borders, width: { size: 1800, type: WidthType.DXA }, shading: { fill: "FBF0F5", type: ShadingType.CLEAR }, margins: { top: 80, bottom: 80, left: 150, right: 150 }, children: [new Paragraph({ children: [new TextRun({ text: "Prioridade", bold: true, size: 20, font: "Arial" })] })] }),
          new TableCell({ borders, width: { size: 6960, type: WidthType.DXA }, margins: { top: 80, bottom: 80, left: 150, right: 150 }, children: [new Paragraph({ children: [new TextRun({ text: r.prioridade, size: 20, font: "Arial", bold: true })] })] }),
        ]}),
      ]
    });
  }
  
  const detailBlocks = [];
  requisitos.forEach(r => {
    detailBlocks.push(rfDetailTable(r));
    detailBlocks.push(new Paragraph({ spacing: { after: 160 }, children: [] }));
  });
  
  const doc = new Document({
    numbering: {
      config: [
        { reference: "bullets", levels: [{ level: 0, format: LevelFormat.BULLET, text: "\u2022", alignment: AlignmentType.LEFT, style: { paragraph: { indent: { left: 720, hanging: 360 } } } }] },
      ]
    },
    styles: {
      default: { document: { run: { font: "Arial", size: 20 } } },
      paragraphStyles: [
        { id: "Heading1", name: "Heading 1", basedOn: "Normal", next: "Normal", quickFormat: true,
          run: { size: 34, bold: true, font: "Arial", color: "C0185C" },
          paragraph: { spacing: { before: 300, after: 200 }, outlineLevel: 0 } },
        { id: "Heading2", name: "Heading 2", basedOn: "Normal", next: "Normal", quickFormat: true,
          run: { size: 26, bold: true, font: "Arial", color: "7B1034" },
          paragraph: { spacing: { before: 240, after: 120 }, outlineLevel: 1 } },
      ]
    },
    sections: [{
      properties: {
        page: {
          size: { width: 12240, height: 15840 },
          margin: { top: 1080, right: 1080, bottom: 1080, left: 1080 }
        }
      },
      headers: {
        default: new Header({
          children: [
            new Paragraph({
              border: { bottom: { style: BorderStyle.SINGLE, size: 6, color: "C0185C" } },
              spacing: { after: 120 },
              children: [
                new TextRun({ text: "Ship-Smart Analytics  |  Documentação de Requisitos Funcionais", size: 18, font: "Arial", color: "888888" })
              ]
            })
          ]
        })
      },
      footers: {
        default: new Footer({
          children: [
            new Paragraph({
              border: { top: { style: BorderStyle.SINGLE, size: 6, color: "C0185C" } },
              spacing: { before: 120 },
              alignment: AlignmentType.RIGHT,
              children: [
                new TextRun({ text: "Página ", size: 18, font: "Arial", color: "888888" }),
                new TextRun({ children: [PageNumber.CURRENT], size: 18, font: "Arial", color: "888888" }),
                new TextRun({ text: " de ", size: 18, font: "Arial", color: "888888" }),
                new TextRun({ children: [PageNumber.TOTAL_PAGES], size: 18, font: "Arial", color: "888888" }),
              ]
            })
          ]
        })
      },
      children: [
        // Title block
        new Paragraph({
          spacing: { before: 400, after: 60 },
          children: [new TextRun({ text: "SHIP-SMART ANALYTICS", bold: true, size: 40, font: "Arial", color: "C0185C" })]
        }),
        new Paragraph({
          spacing: { after: 60 },
          children: [new TextRun({ text: "Especificação de Requisitos Funcionais", bold: true, size: 28, font: "Arial", color: "2C2C2C" })]
        }),
        new Paragraph({
          border: { bottom: { style: BorderStyle.SINGLE, size: 8, color: "C0185C" } },
          spacing: { after: 300 },
          children: [new TextRun({ text: "Entrega 1 — Documentação e Modelagem", size: 20, font: "Arial", color: "888888" })]
        }),
  
        // Identification table
        new Table({
          width: { size: 9360, type: WidthType.DXA },
          columnWidths: [2000, 3680, 1480, 2200],
          rows: [
            new TableRow({ children: [
              new TableCell({ borders, width: { size: 2000, type: WidthType.DXA }, shading: { fill: "F9D0E0", type: ShadingType.CLEAR }, margins: { top: 80, bottom: 80, left: 150, right: 150 }, children: [new Paragraph({ children: [new TextRun({ text: "Projeto", bold: true, size: 20, font: "Arial", color: "7B1034" })] })] }),
              new TableCell({ borders, width: { size: 3680, type: WidthType.DXA }, margins: { top: 80, bottom: 80, left: 150, right: 150 }, children: [new Paragraph({ children: [new TextRun({ text: "Ship-Smart Analytics", size: 20, font: "Arial" })] })] }),
              new TableCell({ borders, width: { size: 1480, type: WidthType.DXA }, shading: { fill: "F9D0E0", type: ShadingType.CLEAR }, margins: { top: 80, bottom: 80, left: 150, right: 150 }, children: [new Paragraph({ children: [new TextRun({ text: "Versão", bold: true, size: 20, font: "Arial", color: "7B1034" })] })] }),
              new TableCell({ borders, width: { size: 2200, type: WidthType.DXA }, margins: { top: 80, bottom: 80, left: 150, right: 150 }, children: [new Paragraph({ children: [new TextRun({ text: "1.0", size: 20, font: "Arial" })] })] }),
            ]}),
            new TableRow({ children: [
              new TableCell({ borders, width: { size: 2000, type: WidthType.DXA }, shading: { fill: "F9D0E0", type: ShadingType.CLEAR }, margins: { top: 80, bottom: 80, left: 150, right: 150 }, children: [new Paragraph({ children: [new TextRun({ text: "Disciplina", bold: true, size: 20, font: "Arial", color: "7B1034" })] })] }),
              new TableCell({ borders, width: { size: 3680, type: WidthType.DXA }, margins: { top: 80, bottom: 80, left: 150, right: 150 }, children: [new Paragraph({ children: [new TextRun({ text: "SAEP — Sistemas de Análise e Projetos", size: 20, font: "Arial" })] })] }),
              new TableCell({ borders, width: { size: 1480, type: WidthType.DXA }, shading: { fill: "F9D0E0", type: ShadingType.CLEAR }, margins: { top: 80, bottom: 80, left: 150, right: 150 }, children: [new Paragraph({ children: [new TextRun({ text: "Data", bold: true, size: 20, font: "Arial", color: "7B1034" })] })] }),
              new TableCell({ borders, width: { size: 2200, type: WidthType.DXA }, margins: { top: 80, bottom: 80, left: 150, right: 150 }, children: [new Paragraph({ children: [new TextRun({ text: "Maio/2026", size: 20, font: "Arial" })] })] }),
            ]}),
          ]
        }),
  
        new Paragraph({ spacing: { after: 200 }, children: [] }),
  
        // 1. Introdução
        new Paragraph({ heading: HeadingLevel.HEADING_1, children: [new TextRun("1. Introdução")] }),
        bodyText("Este documento apresenta a especificação dos Requisitos Funcionais do sistema Ship-Smart Analytics, plataforma web voltada ao gerenciamento de pacotes e entregas. O sistema permite o controle completo do ciclo de vida dos pacotes: desde o cadastro até a entrega ao destinatário, com rastreamento de status, controle de estoque e alertas automatizados."),
  
        new Paragraph({ spacing: { after: 120 }, children: [] }),
  
        // 1.1 Objetivo
        new Paragraph({ heading: HeadingLevel.HEADING_2, children: [new TextRun("1.1 Objetivo do Sistema")] }),
        bodyText("O Ship-Smart Analytics tem como objetivo principal:"),
        bulletItem("Centralizar o gerenciamento de pacotes e entregas;"),
        bulletItem("Oferecer controle de estoque com alertas de reposição;"),
        bulletItem("Prover autenticação segura com controle de perfis de acesso;"),
        bulletItem("Disponibilizar dashboard com indicadores de desempenho operacional."),
  
        new Paragraph({ spacing: { after: 120 }, children: [] }),
  
        // 1.2 Escopo
        new Paragraph({ heading: HeadingLevel.HEADING_2, children: [new TextRun("1.2 Escopo")] }),
        bodyText("O sistema abrange os módulos de Autenticação, Gestão de Pacotes, Controle de Estoque, Alertas e Dashboard. Não está no escopo desta versão: integração com APIs de transportadoras externas, módulo financeiro completo e aplicativo mobile."),
  
        new Paragraph({ spacing: { after: 120 }, children: [] }),
  
        // 1.3 Definições
        new Paragraph({ heading: HeadingLevel.HEADING_2, children: [new TextRun("1.3 Definições e Siglas")] }),
        new Table({
          width: { size: 9360, type: WidthType.DXA },
          columnWidths: [2000, 7360],
          rows: [
            new TableRow({ children: [
              headerCell("Termo / Sigla", 2000),
              headerCell("Definição", 7360),
            ]}),
            ...[
              ["RF", "Requisito Funcional"],
              ["Admin", "Perfil de administrador com acesso total ao sistema"],
              ["Operador", "Perfil com acesso restrito a consultas e gestão de pacotes"],
              ["Estoque Mínimo", "Quantidade mínima configurada abaixo da qual o sistema emite alerta"],
              ["Status do Pacote", "Estado atual do pacote: Pendente, Em Rota ou Entregue"],
              ["saep_db", "Banco de dados do sistema Ship-Smart Analytics"],
            ].map(([t, d]) => new TableRow({ children: [
              dataCell(t, 2000, true),
              dataCell(d, 7360),
            ]}))
          ]
        }),
  
        new Paragraph({ spacing: { after: 300 }, children: [] }),
  
        // 2. Requisitos Funcionais
        new Paragraph({ heading: HeadingLevel.HEADING_1, children: [new TextRun("2. Requisitos Funcionais")] }),
        bodyText("Os requisitos funcionais estão organizados por módulo e descritos conforme o template padrão de especificação, incluindo entradas, saídas e regras de negócio associadas."),
  
        new Paragraph({ spacing: { after: 160 }, children: [] }),
  
        // 2.1 Resumo
        new Paragraph({ heading: HeadingLevel.HEADING_2, children: [new TextRun("2.1 Resumo dos Requisitos")] }),
        rfTable,
  
        new Paragraph({ spacing: { after: 300 }, children: [] }),
  
        // 2.2 Detalhamento
        new Paragraph({ heading: HeadingLevel.HEADING_2, children: [new TextRun("2.2 Detalhamento dos Requisitos")] }),
        new Paragraph({ spacing: { after: 160 }, children: [] }),
        ...detailBlocks,
  
        new Paragraph({ spacing: { after: 300 }, children: [] }),
  
        // 3. Requisitos Não Funcionais
        new Paragraph({ heading: HeadingLevel.HEADING_1, children: [new TextRun("3. Requisitos Não Funcionais")] }),
        new Table({
          width: { size: 9360, type: WidthType.DXA },
          columnWidths: [900, 1800, 6660],
          rows: [
            new TableRow({ children: [
              headerCell("ID", 900),
              headerCell("Categoria", 1800),
              headerCell("Descrição", 6660),
            ]}),
            ...[
              ["RNF-01", "Desempenho", "O sistema deve carregar a lista de pacotes em no máximo 3 segundos."],
              ["RNF-02", "Segurança", "Senhas devem ser armazenadas com hash bcrypt. Sessões devem expirar após 8 horas de inatividade."],
              ["RNF-03", "Usabilidade", "A interface deve ser responsiva e compatível com navegadores modernos (Chrome, Firefox, Edge)."],
              ["RNF-04", "Disponibilidade", "O sistema deve ter disponibilidade mínima de 99% no horário comercial (07h-22h)."],
              ["RNF-05", "Manutenibilidade", "O código deve seguir padrão MVC, facilitando manutenção e evolução."],
            ].map(([id, cat, desc]) => new TableRow({ children: [
              dataCell(id, 900, true),
              dataCell(cat, 1800),
              dataCell(desc, 6660),
            ]}))
          ]
        }),
  
        new Paragraph({ spacing: { after: 300 }, children: [] }),
  
        // 4. Casos de Uso
        new Paragraph({ heading: HeadingLevel.HEADING_1, children: [new TextRun("4. Casos de Uso Principais")] }),
        new Table({
          width: { size: 9360, type: WidthType.DXA },
          columnWidths: [900, 2000, 1800, 4660],
          rows: [
            new TableRow({ children: [
              headerCell("UC", 900),
              headerCell("Nome", 2000),
              headerCell("Ator", 1800),
              headerCell("RF Relacionados", 4660),
            ]}),
            ...[
              ["UC-01", "Autenticar no Sistema", "Usuário", "RF-01, RF-02, RF-03"],
              ["UC-02", "Gerenciar Pacotes", "Operador / Admin", "RF-04, RF-05, RF-06, RF-07, RF-08"],
              ["UC-03", "Controlar Estoque", "Admin", "RF-09, RF-10, RF-11"],
              ["UC-04", "Visualizar Dashboard", "Usuário", "RF-12"],
            ].map(([uc, nome, ator, rfs]) => new TableRow({ children: [
              dataCell(uc, 900, true),
              dataCell(nome, 2000),
              dataCell(ator, 1800),
              dataCell(rfs, 4660),
            ]}))
          ]
        }),
      ]
    }]
  });
  
  Packer.toBuffer(doc).then(buf => {
    fs.writeFileSync('/home/claude/entrega1_requisitos.docx', buf);
    console.log('Done: entrega1_requisitos.docx');
  });