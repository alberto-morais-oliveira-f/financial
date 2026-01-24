# Documentação de Ícones do Sistema

Este documento descreve como utilizar os conjuntos de ícones disponíveis no projeto. O sistema utiliza duas bibliotecas principais: **Feather Icons** e **Font Awesome**.

## 1. Feather Icons

Feather é uma coleção de ícones SVG de código aberto, simples e bonitos. Eles são leves e renderizados via JavaScript.

### Como Usar

Para adicionar um ícone Feather, utilize a tag `<i>` com o atributo `data-feather` contendo o nome do ícone.

**Estrutura:**
```html
<i data-feather="nome-do-icone"></i>
```

**Exemplos:**

*   Ícone de "editar":
    ```html
    <i data-feather="edit-2"></i>
    ```

*   Ícone de "lixeira":
    ```html
    <i data-feather="trash-2"></i>
    ```

*   Ícone de "usuário":
    ```html
    <i data-feather="user"></i>
    ```

O script `feather.replace()` presente no layout principal irá procurar por esses atributos e substituir a tag `<i>` pelo código SVG correspondente do ícone.

### Lista de Ícones

A lista completa de ícones e seus respectivos nomes pode ser encontrada no site oficial do Feather Icons.

*   **Website Oficial:** [https://feathericons.com/](https://feathericons.com/)

## 2. Font Awesome

Font Awesome é uma das bibliotecas de ícones mais populares, oferecendo uma vasta gama de ícones vetoriais e logotipos sociais. No projeto, utilizamos a versão baseada em fontes (font-based).

### Como Usar

Para usar um ícone Font Awesome, utilize a tag `<i>` com as classes CSS correspondentes. A classe base é `fa` ou `fas` (sólido), `far` (regular), `fal` (light), `fad` (duotone) ou `fab` (marcas), seguida pela classe específica do ícone (`fa-nome-do-icone`).

**Estrutura:**
```html
<i class="prefixo fa-nome-do-icone"></i>
```

**Exemplos:**

*   Ícone de "usuário" (regular):
    ```html
    <i class="far fa-user"></i>
    ```

*   Ícone de "salvar" (regular):
    ```html
    <i class="far fa-save"></i>
    ```

*   Ícone de "sino" (sólido):
    ```html
    <i class="fas fa-bell"></i>
    ```

### Lista de Ícones

Consulte o site oficial do Font Awesome para pesquisar e encontrar os ícones que você precisa, juntamente com seus prefixos e nomes corretos.

*   **Website Oficial:** [https://fontawesome.com/](https://fontawesome.com/)

## Boas Práticas

*   **Consistência**: Tente manter a consistência visual. Se uma determinada seção da interface já utiliza um estilo de ícone (ex: Feather), prefira continuar com ele.
*   **Tamanho e Cor**: O tamanho e a cor dos ícones podem ser customizados via CSS, utilizando as classes `font-size` e `color`.
    ```css
    .meu-icone-customizado {
        font-size: 24px;
        color: #3b3f5c;
    }
    ```
    ```html
    <i data-feather="home" class="meu-icone-customizado"></i>
    ```
*   **Performance**: Feather Icons (SVG) geralmente oferecem melhor performance e qualidade de renderização em comparação com ícones de fonte. Dê preferência a eles para ícones de interface gerais. Use Font Awesome quando precisar de ícones específicos que não estão disponíveis no Feather, especialmente logotipos de marcas.
