document.addEventListener('DOMContentLoaded', () => {

    function openModal(modal) { if (modal) modal.style.display = 'block'; }
    function closeModal(modal) { if (modal) modal.style.display = 'none'; }

    document.querySelectorAll('[data-modal-target]').forEach(trigger => {
        trigger.addEventListener('click', () => {
            const modal = document.querySelector(trigger.dataset.modalTarget);
            openModal(modal);

            if (trigger.classList.contains('delete-btn')) {
                modal.querySelector('#delete_id_servico').value = trigger.dataset.id;
            }

            if (trigger.classList.contains('edit-btn')) {
                const rowData = JSON.parse(trigger.dataset.rowData);
                populateEditModal(modal, rowData);
            }
        });
    });

    document.querySelectorAll('.modal-close-btn').forEach(button => {
        button.addEventListener('click', () => closeModal(button.closest('.modal')));
    });

    window.addEventListener('click', (event) => {
        if (event.target.matches('.modal')) {
            closeModal(event.target);
        }
    });

    function setupProductSelector(modalId, selectClass, quantityClass, addButtonClass, listClass) {
        const modal = document.getElementById(modalId);
        if (!modal) return;

        const btnAdd = modal.querySelector(`.${addButtonClass}`);
        const select = modal.querySelector(`.${selectClass}`);
        const inputQty = modal.querySelector(`.${quantityClass}`);
        const listContainer = modal.querySelector(`.${listClass}`);

        btnAdd.addEventListener('click', () => {
            const selectedOption = select.options[select.selectedIndex];
            const produtoId = selectedOption.value;
            const produtoNome = selectedOption.text.split(' (Estoque:')[0];
            const maxEstoque = parseInt(selectedOption.dataset.max, 10);
            let quantidade = parseInt(inputQty.value, 10);

            if (!produtoId || isNaN(quantidade) || quantidade <= 0) {
                alert('Selecione um produto e uma quantidade válida.');
                return;
            }
            if (quantidade > maxEstoque) {
                alert(`Estoque insuficiente. Máximo disponível: ${maxEstoque}`);
                return;
            }
            if (listContainer.querySelector(`.produto-item[data-id="${produtoId}"]`)) {
                 alert('Este produto já foi adicionado.');
                 return;
            }

            appendProductItem(listContainer, produtoId, quantidade, produtoNome);
            select.selectedIndex = 0;
            inputQty.value = '';
        });

        listContainer.addEventListener('click', (event) => {
            if (event.target.classList.contains('produto-item-remove')) {
                event.target.closest('.produto-item').remove();
            }
        });
    }

    function appendProductItem(container, id, qtd, nome) {
        const itemDiv = document.createElement('div');
        itemDiv.classList.add('produto-item');
        itemDiv.dataset.id = id;
        itemDiv.innerHTML = `
            <span>${qtd}x ${nome}</span>
            <span class="produto-item-remove" title="Remover">X</span>
            <input type="hidden" name="produtos_id[]" value="${id}">
            <input type="hidden" name="produtos_qtd[]" value="${qtd}">
        `;
        container.appendChild(itemDiv);
    }

    setupProductSelector('addServicoModal', 'produtoDisponivelAdd', 'produtoQuantidadeAdd', 'btnAddProdutoItemAdd', 'produtosSelecionadosListaAdd');
    setupProductSelector('editServicoModal', 'produtoDisponivelEdit', 'produtoQuantidadeEdit', 'btnAddProdutoItemEdit', 'produtosSelecionadosListaEdit');

    function populateEditModal(modal, data) {
        modal.querySelector('#edit_ordem_id').value = data.NumeroOrdem;
        modal.querySelector('#edit_nome_cli').value = data.NomeCli;
        modal.querySelector('#edit_data_abertura').value = data.datadeabertura;
        modal.querySelector('#edit_situacao').value = data.situacao;
        modal.querySelector('#edit_descricao').value = data.Descricao;

        const listContainer = modal.querySelector('.produtosSelecionadosListaEdit');
        listContainer.innerHTML = ''; // Limpa itens de uma edição anterior

        try {
            const produtos = JSON.parse(data.produtos);
            if (produtos && Array.isArray(produtos)) {
                produtos.forEach(p => {
                    appendProductItem(listContainer, p.id, p.quantidade, p.nome);
                });
            }
        } catch (e) {
            console.error("Erro ao processar JSON de produtos:", e);
        }
    }
});