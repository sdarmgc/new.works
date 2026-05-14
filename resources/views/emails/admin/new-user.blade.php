<x-mail::message>
# New User Registered

A new account has been created and verified(email) on WORKS application.

**User Details:**
- **Name:** {{ $user->name }}
- **Email:** {{ $user->email }}
- **Registered At:** {{ $user->created_at->toDayDateTimeString() }}

<x-mail::button :url="config('app.url') . '/admin/users'">
View User Directory
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>