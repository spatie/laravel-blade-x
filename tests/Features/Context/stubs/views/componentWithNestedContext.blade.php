<context :user="$user">
    <user-name />

    <context :user="$nestedUser">
        <user-name />
    </context>

    <user-name />
</context>
