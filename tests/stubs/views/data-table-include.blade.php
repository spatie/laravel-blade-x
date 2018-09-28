<data-table color="blue" items="{{ $users }}">
    <slot name="thead">
        <th>Name</th>
        <th>E-mail</th>
    </slot>
    <slot name="tbody">
        <td>{{ $item['name'] }}</td>
        <td>{{ $item['email'] }}</td>
    </slot>
</data-table>
