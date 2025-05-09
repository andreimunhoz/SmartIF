<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Abrir Chamado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mt-5">
        <div class="max-w-2xl mx-auto bg-white p-5 rounded shadow">
            <h1 class="text-2xl font-bold mb-4">Abrir Chamado</h1>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('chamado.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="nome" class="form-label">Nome</label>
                    <input type="text" class="form-control" name="nome" value="{{ old('nome') }}" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                </div>

                <div class="mb-3">
                    <label for="descricao" class="form-label">Descrição</label>
                    <textarea class="form-control" name="descricao" rows="3" required>{{ old('descricao') }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="patrimonio" class="form-label">Nº Patrimonial</label>
                    <input type="text" class="form-control" name="patrimonio" value="{{ old('patrimonio', '0') }}">
                </div>

                <div class="mb-3">
                    <label for="sala" class="form-label">Sala / Local</label>
                    <input type="text" class="form-control" name="sala" value="{{ old('sala') }}" required>
                </div>

                <div class="mb-3">
                    <label for="ramal" class="form-label">Ramal</label>
                    <input type="text" class="form-control" name="ramal" value="{{ old('ramal') }}" required>
                </div>

                <div class="mb-3">
                    <label for="departamento_id" class="form-label">Departamento</label>
                    <select class="form-select" name="departamento_id" required>
                        <option value="">Selecione</option>
                        @foreach($departamentos as $departamento)
                            <option value="{{ $departamento->id }}" {{ old('departamento_id') == $departamento->id ? 'selected' : '' }}>
                                {{ $departamento->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Enviar Chamado</button>
            </form>
        </div>
    </div>
</body>
</html>
