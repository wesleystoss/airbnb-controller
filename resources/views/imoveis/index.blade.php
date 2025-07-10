@extends('layout')

@section('content')
<div x-data="{
    showEdit: false,
    editId: null,
    editNome: '',
    showShare: null,
    shareEmail: '',
    openEditModal(id, nome) {
        this.editId = id;
        this.editNome = nome;
        this.showEdit = true;
        this.$nextTick(() => {
            this.$refs.editForm.action = `/imoveis/${id}`;
        });
    }
}" class="max-w-2xl mx-auto mt-8 max-w-md lg:max-w-3xl xl:max-w-5xl mx-auto">
    <div class="bg-white/95 backdrop-blur-md rounded-2xl shadow-xl border border-white/20 p-6 mb-6">
        <h2 class="text-2xl font-bold mb-4 text-[#FF385C]">Cadastrar Novo Imóvel</h2>
        <form method="POST" action="{{ route('imoveis.store') }}">
            @csrf
            <div class="mb-4">
                <label for="nome" class="block text-gray-700 font-semibold mb-2">Nome do Imóvel</label>
                <input type="text" id="nome" name="nome" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF385C]" placeholder="Ex: Casa de Praia" required/>
            </div>
            <div class="mb-4">
                <label for="email_compartilhado" class="block text-gray-700 font-semibold mb-2">Compartilhar com (e-mails, separados por vírgula)</label>
                <input type="text" id="email_compartilhado" name="email_compartilhado" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF385C]" placeholder="Digite um ou mais e-mails separados por vírgula" />
            </div>
            <button type="submit" class="btn-action btn-action-success">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg> 
                Cadastrar
            </button>
        </form>
    </div>
    <div class="bg-white/95 backdrop-blur-md rounded-2xl shadow-xl border border-white/20 p-6 mb-6">
        <h2 class="text-xl font-bold mb-4 text-[#FF385C]">Imóveis Cadastrados</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Compartilhado com</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ajustar compartilhamento</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($imoveis as $i => $imovel)
                        <tr class="{{ $i % 2 === 0 ? 'bg-gray-50' : 'bg-white' }} transition hover:bg-[#FFF1F2]">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $imovel->nome }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button type="button" @click="openEditModal({{ $imovel->id }}, '{{ addslashes($imovel->nome) }}')" class="btn-action btn-action-edit mr-2 transition hover:scale-105">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg> 
                                    Editar
                                </button>
                                <form action="{{ route('imoveis.destroy', $imovel) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-action-delete transition hover:scale-105" onclick="return confirm('Tem certeza que deseja excluir este imóvel?')">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg> 
                                        Excluir
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs">
                                @if($imovel->compartilhamentos->isEmpty())
                                    <span class="text-gray-400">-</span>
                                @else
                                    @foreach($imovel->compartilhamentos as $comp)
                                        <span class="inline-block bg-blue-50 text-blue-700 rounded-full px-2 py-1 mr-1 mb-1">{{ $comp->usuarioCompartilhado->email }}</span>
                                    @endforeach
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs">
                                <button type="button" @click="showShare = {{ $imovel->id }}; shareEmail = '';" class="btn-action btn-action-warning transition hover:scale-105">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg> 
                                    Compartilhar
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-400">Nenhum imóvel cadastrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal de Compartilhamento (fora da tabela) -->
    @foreach($imoveis as $imovel)
        <div x-show="showShare === {{ $imovel->id }}" style="display: none;" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-40">
            <div class="bg-white rounded-2xl shadow-xl p-8 w-full max-w-md relative">
                <button @click="showShare = null" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-2xl">&times;</button>
                <h2 class="text-xl font-bold mb-4 text-[#FF385C]">Compartilhamento de Imóvel</h2>
                <div class="mb-4">
                    <div class="mb-2 font-semibold text-sm">Usuários compartilhados:</div>
                    @if($imovel->compartilhamentos->isEmpty())
                        <span class="text-gray-400">Nenhum compartilhamento</span>
                    @else
                        @foreach($imovel->compartilhamentos as $comp)
                            <form action="{{ route('imoveis.compartilhamento.remover', $comp->id) }}" method="POST" class="inline-block mr-2 mb-2">
                                @csrf
                                @method('DELETE')
                                <span class="inline-block bg-blue-50 text-blue-700 rounded-full px-2 py-1">{{ $comp->usuarioCompartilhado->email }}</span>
                                <button type="submit" class="ml-1 text-red-500 hover:text-red-700 font-bold">&times;</button>
                            </form>
                        @endforeach
                    @endif
                </div>
                <form action="{{ route('imoveis.compartilhamento.adicionar', $imovel->id) }}" method="POST" class="flex gap-2 items-center">
                    @csrf
                    <input type="text" name="email" x-model="shareEmail" placeholder="Novo e-mail para compartilhar" class="flex-1 px-3 py-2 border rounded focus:ring-2 focus:ring-[#FF385C]" required />
                    <button type="submit" class="btn-action btn-action-success transition hover:scale-105">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg> 
                        Adicionar
                    </button>
                </form>
            </div>
        </div>
    @endforeach

    <!-- Modal de Edição -->
    <div x-show="showEdit" style="display: none;" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-40">
        <div class="bg-white rounded-2xl shadow-xl p-8 w-full max-w-md relative">
            <button @click="showEdit = false" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-2xl">&times;</button>
            <h2 class="text-xl font-bold mb-4 text-[#FF385C]">Editar Imóvel</h2>
            <form method="POST" :action="`{{ url('/imoveis') }}/${editId}`" x-ref="editForm">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="edit-nome" class="block text-gray-700 font-semibold mb-2">Nome do Imóvel</label>
                    <input type="text" id="edit-nome" name="nome" x-model="editNome" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF385C]" required />
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="showEdit = false" class="btn-action btn-action-details">Cancelar</button>
                    <button type="submit" class="btn-action btn-action-success">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 11l6 6M3 21h6a2 2 0 002-2v-6a2 2 0 00-2-2H3v8z" />
                        </svg> 
                        Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('imoveisPage', () => ({
            showEdit: false,
            editId: null,
            editNome: '',
            showShare: null,
            shareEmail: '',
            openEditModal(id, nome) {
                this.editId = id;
                this.editNome = nome;
                this.showEdit = true;
                this.$nextTick(() => {
                    this.$refs.editForm.action = `/imoveis/${id}`;
                });
            }
        }))
    })
</script> 