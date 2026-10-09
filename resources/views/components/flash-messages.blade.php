@foreach(['success' => 'green', 'error' => 'red'] as $type => $color)
    @if(session($type))<div role="{{ $type === 'error' ? 'alert' : 'status' }}" class="mb-5 rounded-xl border px-4 py-3 text-sm {{ $type === 'error' ? 'bg-red-50 border-red-200 text-red-800' : 'bg-green-50 border-green-200 text-green-800' }}">{{ session($type) }}</div>@endif
@endforeach
@if($errors->any())
    <div role="alert" class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800"><p class="font-semibold mb-2">Revisa los siguientes datos:</p><ul class="list-disc pl-5 space-y-1">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
@endif
