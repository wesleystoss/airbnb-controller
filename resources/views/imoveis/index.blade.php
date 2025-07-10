@extends('layout')

@section('content')
<div x-data="{
    showEdit: false,
    editId: null,
    editNome: '',
    openEditModal(id, nome) {
        this.editId = id;
        this.editNome = nome;
        this.showEdit = true;
        this.$nextTick(() => {
            this.$refs.editForm.action = `/imoveis/${id}`;
        });
    }
}" class="max-w-2xl mx-auto mt-8 max-w-md lg:max-w-3xl xl:max-w-5xl mx-auto">
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
        <h2 class="text-2xl font-bold mb-4 text-[#FF385C]">Cadastrar Novo Imóvel</h2>
        <form method="POST" action="{{ route('imoveis.store') }}">
            @csrf
            <div class="mb-4">
                <label for="nome" class="block text-gray-700 font-semibold mb-2">Nome do Imóvel</label>
                <input type="text" id="nome" name="nome" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF385C]" placeholder="Ex: Casa de Praia" required/>
            </div>
            <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#FF385C] to-[#e11d48] text-white rounded-full font-semibold shadow-lg hover:shadow-xl transition-all duration-300 btn-press">Cadastrar</button>
        </form>
    </div>
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <h2 class="text-xl font-bold mb-4 text-[#FF385C]">Imóveis Cadastrados</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($imoveis as $i => $imovel)
                        <tr class="{{ $i % 2 === 0 ? 'bg-gray-50' : 'bg-white' }}">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $imovel->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $imovel->nome }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button type="button" @click="openEditModal({{ $imovel->id }}, '{{ addslashes($imovel->nome) }}')" class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full font-semibold shadow-sm hover:bg-blue-200 transition-all duration-200 mr-2">Editar</button>
                                <form action="{{ route('imoveis.destroy', $imovel) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 bg-red-100 text-red-700 rounded-full font-semibold shadow-sm hover:bg-red-200 transition-all duration-200">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-6 py-4 text-center text-gray-400">Nenhum imóvel cadastrado.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

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
                    <button type="button" @click="showEdit = false" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-full font-semibold hover:bg-gray-300 transition-all">Cancelar</button>
                    <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#FF385C] to-[#e11d48] text-white rounded-full font-semibold shadow-lg hover:shadow-xl transition-all duration-300 btn-press">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 