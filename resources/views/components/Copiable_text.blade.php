<p class="flex" {!! isset($title) ? 'title="' . $title . '"' : '' !!}>
    {!! isset($titre) ? '<strong class="text-nowrap">' . $titre . '</strong>' : '' !!}
    <span class="copiable_parent w-fit flex" onclick="copyToClipboard('{{ $text }}')">
        {!! '<span class="copiable">&nbsp;' . $text . '</span>' !!}
        <x-icon :size="1" type="copy" class="hidden_copiable icons-no_hover" />
        <small class="hidden_copiable_small">Copier</small>
    </span>
</p>
