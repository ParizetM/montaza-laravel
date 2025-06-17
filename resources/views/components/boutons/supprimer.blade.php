@props([
    'buttonText' => 'Supprimer',
    'customButton' => false,
    'modalTitle' => 'Supprimer',
    'confirmButtonText' => 'Confirmer',
    'cancelButtonText' => 'Annuler',
    'modalName' => 'deleteModal-'.Str::random(8).'-'.Str::random(8).'-'.Str::random(8),
    'formAction' => '',
    'errorName' => 'delete',
    'userInfo' => 'Cette action est irréversible. Êtes-vous sûr de vouloir supprimer cet élément ?',
    'onSubmit' => null,
])
@if ($customButton == false)
<x-danger-button x-data=""
    x-on:click.prevent="$dispatch('open-modal', '{{ $modalName }}')">
    {!! $buttonText !!}
</x-danger-button>
@else
<div
    x-data=""
    x-on:click.prevent="$dispatch('open-modal', '{{ $modalName }}')"
    class="inline-block">
 {!! $customButton !!}
 </div>
@endif

<x-modal name="{{ $modalName }}" :show="$errors->has($errorName)" focusable>
    <form method="post" action="{{ $formAction }}" class="p-6"
          @if($onSubmit || !$formAction)
          x-on:submit.prevent="
              @if($onSubmit)
                  {{ $onSubmit }}
              @endif
              @if(!$formAction)
                  return false;
              @else
                  $el.submit();
              @endif
          "
          @endif>
        @csrf
        @method('delete')
            <a x-on:click="$dispatch('close')">
        <x-icons.close class="float-right mb-1 icons" size="1.5" unfocus />
    </a>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {!! $modalTitle !!}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {!! $userInfo !!}
        </p>

        <div class="mt-6 flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">
                {!! $cancelButtonText !!}
            </x-secondary-button>

            <x-danger-button class="ms-3">
                {!! $confirmButtonText !!}
            </x-danger-button>
        </div>
    </form>
</x-modal>
