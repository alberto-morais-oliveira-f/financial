# Documentação para Criação de Formulários com Bootstrap

Este documento descreve o padrão para a criação de formulários utilizando Bootstrap e templates Blade no projeto.

## Estrutura Base do Template

Para criar uma nova tela de formulário, utilize o layout principal `layouts.master` e defina as seções necessárias.

```blade
@extends('layouts.master', ['title' => 'Título da Página'])

@section('styles')
    {{-- Adicione aqui seus estilos CSS específicos para a página --}}
@endsection

@section('content')
    {{-- O conteúdo principal do seu formulário vai aqui --}}
@endsection

@section('scripts')
    {{-- Adicione aqui seus scripts JavaScript específicos para a página --}}
@endsection
```

### Seções do Template

*   `@extends('layouts.master', ['title' => 'Título da Página'])`: Define que a view herda do layout mestre e define o título da página.
*   `@section('styles')`: Use esta seção para incluir quaisquer folhas de estilo (CSS) adicionais ou customizadas. Prefira usar `@vite` para incluir assets do SCSS.
*   `@section('content')`: Esta é a seção principal onde todo o conteúdo do formulário (campos, botões, etc.) deve ser colocado.
*   `@section('scripts')`: Use esta seção para incluir scripts JavaScript.

## Estrutura do Conteúdo do Formulário

O conteúdo do formulário deve ser organizado dentro de "widgets" ou "cards" para manter a consistência visual da interface.

### Exemplo de Card Básico

```blade
<div class="row">
    <div class="col-lg-12 layout-spacing layout-top-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>Título do Formulário</h4>
                    </div>
                </div>
            </div>
            <div class="widget-content widget-content-area">

                {{-- Seu formulário aqui --}}
                <form>
                    {{-- Campos do formulário --}}
                </form>

            </div>
        </div>
    </div>
</div>
```

## Tipos de Layout de Formulário

O projeto utiliza as classes de grid e layout do Bootstrap para estruturar os formulários. Abaixo estão os exemplos mais comuns.

### 1. Formulário Padrão (Stacked)

Os campos são empilhados verticalmente.

```blade
<form>
    <div class="row mb-4">
      <div class="col-sm-12">
        <label for="inputEmail1">Email</label>
        <input type="email" class="form-control" id="inputEmail1" placeholder="Endereço de email *">
      </div>
    </div>
    <div class="row mb-4">
      <div class="col-sm-12">
        <label for="inputPassword1">Senha</label>
        <input type="password" class="form-control" id="inputPassword1" placeholder="Senha *">
      </div>
    </div>
    <button type="submit" class="btn btn-primary">Enviar</button>
</form>
```

### 2. Formulário Horizontal

Os labels ficam ao lado dos campos. Use as classes de grid do Bootstrap (`row`, `col-sm-*`, etc.) e a classe `col-form-label` para os labels.

```blade
<form>
    <div class="row mb-3">
      <label for="inputEmail2" class="col-sm-2 col-form-label">Email</label>
      <div class="col-sm-10">
        <input type="email" class="form-control" id="inputEmail2">
      </div>
    </div>
    <div class="row mb-3">
      <label for="inputPassword2" class="col-sm-2 col-form-label">Senha</label>
      <div class="col-sm-10">
        <input type="password" class="form-control" id="inputPassword2">
      </div>
    </div>
    <button type="submit" class="btn btn-primary">Entrar</button>
</form>
```

### 3. Formulário com Grid (Gutters)

Para formulários mais complexos, use o sistema de grid do Bootstrap para alinhar os campos em colunas. A classe `g-3` no `<form>` adiciona espaçamento (gutters) entre as colunas.

```blade
<form class="row g-3">
    <div class="col-md-6">
        <label for="inputEmail4" class="form-label">Email</label>
        <input type="email" class="form-control" id="inputEmail4">
    </div>
    <div class="col-md-6">
        <label for="inputPassword4" class="form-label">Senha</label>
        <input type="password" class="form-control" id="inputPassword4">
    </div>
    <div class="col-12">
        <label for="inputAddress" class="form-label">Endereço</label>
        <input type="text" class="form-control" id="inputAddress" placeholder="Rua Principal, 1234">
    </div>
    <div class="col-md-6">
        <label for="inputCity" class="form-label">Cidade</label>
        <input type="text" class="form-control" id="inputCity">
    </div>
    <div class="col-md-4">
        <label for="inputState" class="form-label">Estado</label>
        <select id="inputState" class="form-select">
            <option selected>Escolha...</option>
            <option>...</option>
        </select>
    </div>
    <div class="col-md-2">
        <label for="inputZip" class="form-label">CEP</label>
        <input type="text" class="form-control" id="inputZip">
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary">Salvar</button>
    </div>
</form>
```

## Boas Práticas e Convenções

1.  **Form Builder**: Embora os exemplos usem HTML puro para clareza, o projeto utiliza o pacote `laravelcollective/html`. Dê preferência a ele para gerar formulários, pois ele lida com tokens CSRF, spoofing de método (`PUT`/`DELETE`) e preenchimento de valores antigos (`old()`) automaticamente.
    ```php
    // Exemplo com Form Builder
    {!! Form::open(['route' => 'sua.rota', 'class' => 'row g-3']) !!}
        <div class="col-md-6">
            {!! Form::label('email', 'Email', ['class' => 'form-label']) !!}
            {!! Form::email('email', null, ['class' => 'form-control', 'id' => 'inputEmail4']) !!}
        </div>
    {!! Form::close() !!}
    ```

2.  **Validação**: A validação dos dados deve ser feita no backend usando **Form Requests**. Isso mantém os Controllers limpos.

3.  **Tradução**: Todos os textos visíveis para o usuário (labels, placeholders, mensagens de ajuda) devem estar em **Português** e, idealmente, usar o sistema de localização do Laravel (`__('messages.key')`).

4.  **Nomenclatura**: Nomes de `id` e `name` dos campos do formulário devem ser em **Inglês**.

5.  **Assets**: Evite `<style>` e `<script>` inline. Adicione CSS e JS nas seções `@styles` e `@scripts`, respectivamente, utilizando o Vite sempre que possível.
