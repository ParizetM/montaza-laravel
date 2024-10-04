<select id="role_id" name="role_id" class="block w-full {{isset($class) ? $class : 'select'}}" required>
    @foreach ($entites as $entite)
        <optgroup label="{{ $entite->name }}">
            @foreach ($entite->roles as $role)
                <option value="{{ $role->id }}"
                    @if (isset($user))
                    {{ (old('role_id') == $role->id ? 'selected' : $user->role_id == $role->id) ? 'selected' : '' }}>
                    @else
                    {{ old('role_id') == $role->id ? 'selected' : '' }}>
                    @endif
                    {{ $role->name }}
                </option>
            @endforeach
        </optgroup>
    @endforeach
</select>
