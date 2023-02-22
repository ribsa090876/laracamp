@component('mail::message')
# Register Camp: {{$checkout->Camps->title}}

Hi {{$checkout->User->name}}
<br>
Thank you for register  on <b>{{$checkout->Camps->title}}</b>, please see payment instruction  by click  the button below.

@component('mail::button', ['url' => route('dashboard')])
My Dashoard
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
