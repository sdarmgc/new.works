<x-mail::message>
# New Contact Request

You have received a new message from your website contact form.

**Name:** {{ $formData['name'] }}  
**Email:** {{ $formData['email'] }}  
**Subject:** {{ $formData['subject'] }}  

**Message:**  
{{ $formData['message'] }}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>