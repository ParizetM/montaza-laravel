@php
    $quickMatiereModalId = 'quickMatiereModal-' . time();
@endphp

<button x-data {{ $attributes->merge(['type' => 'button', 'class' => 'btn']) }}
    x-on:click.prevent="$dispatch('open-modal', '{{ $quickMatiereModalId }}')"
    onclick="showquickMatiereModal('{{ $quickMatiereModalId }}')">
    Ajouter une Matière
</button>

<!-- Modal -->
<x-modal id="{{ $quickMatiereModalId }}" name="{{ $quickMatiereModalId }}" title="Quick Create Matiere">
    <div id="modal-body-{{ $quickMatiereModalId }}">
    <div id="loading-spinner"
        class=" m-6 inset-0 bg-none bg-opacity-75 flex items-center justify-center z-50 h-32 w-full">
        <div class="loader ease-linear rounded-full border-8 border-t-8 border-gray-200 h-32 w-32"></div>
    </div>
    <style>
        .loader {
            border-top-color: #3498db;
            animation: spinner 1.5s linear infinite;
        }

        @keyframes spinner {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
    </div>
</x-modal>
<script>
    function showquickMatiereModal(id) {
        var modalBody = document.getElementById('modal-body-'+id);
        console.log(modalBody);
        console.log('/matieres/quickcreate/' + id);
        fetch('/matieres/quickcreate/' + id)
            .then(response => response.text())
            .then(html => {
                modalBody.innerHTML = html;
            })
            .catch(error => {
                console.error('Error loading modal content:', error);
                modalBody.innerHTML = '<p>Error loading content.</p>';
            });
    }
</script>
