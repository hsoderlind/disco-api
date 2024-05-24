<x-layouts.mail-layout>
    <p>Hej {{ $name }}!</p>
    <p>{{ $inviterName }} har bjudit in dig att skapa konto på {{ env('APP_NAME') }}.</p>
    <p></p>
    <div class="my-5"><x-link-button :href="$registerUrl">Registrera konto</x-link-button></div>
    <p>Vänligen,<br />{{ env('APP_NAME') }}</p>
</x-layouts.mail-layout>
