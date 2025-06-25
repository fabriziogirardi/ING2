@php
    use App\Models\Customer;
    use App\Models\Employee;
    use App\Models\Person;
@endphp
<x-layouts.app>
    <x-slot:title>
        {{ $discussion->title }}
    </x-slot:title>
    <div class="bg-gray-50 h-screen">
        <div class="bg-white grid grid-cols-2 gap-x-4 p-4 max-h-screen">
            <div class="px-3 bg-gray-100 rounded-lg shadow-md max-h-screen h-screen">
                <div class="flex flex-row space-x-4 items-center my-2">
                    <h3>{{ Customer::find($discussion->customer_id)->person->full_name }}</h3>
                    <h3 class="text-sm text-gray-500">{{ $discussion->created_at->diffForHumans() }}</h3>
                </div>
                <div class="flex flex-col h-full relative">
                    <div>
                        <h1 class="text-5xl font-bold">{{ $discussion->title }}</h1>
                    </div>
                    <div class="text-lg overflow-y-auto h-2/4 mt-4">
                        <p>{{ $discussion->content }}</p>
                    </div>
                    <div>
                        <div class="flex flex-row space-x-4 mt-2">
                            <div>
                                @if(Auth::getCurrentGuard() === 'customer')
                                    <x-elements.link-button href="{{ route('forum.discussions.edit', ['discussion' => $discussion->id]) }}">
                                        <x-slot:text>Editar Discusión</x-slot:text>
                                    </x-elements.link-button>
                                @endif
                            </div>
                            <div>
                                @if(Auth::getCurrentGuard() === 'manager')
                                    <form action="{{ route('forum.discussions.destroy', ['discussion' => $discussion]) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="relative inline-flex items-center justify-center p-0.5 me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-rose-400 to-red-600 group-hover:from-rose-400 group-hover:to-red-600 hover:text-white">
                                            <span class="relative px-5 py-2.5 transition-all ease-in duration-75 bg-white rounded-md group-hover:bg-transparent w-full">
                                                Eliminar
                                            </span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-3 bg-gray-100 rounded-lg shadow-md">
                <livewire:customer.forum.create-reply-manage :discussion="$discussion"/>

                @foreach($replies as $reply)
                    <div class="bg-white p-4 my-2 rounded-lg shadow flex flex-row justify-between">
                        <div class="w-3/4">
                            <div class="flex flex-row gap-x-2 items-center">
                                <h4>{{ Person::find($reply->person_id)->first_name }} {{ Person::find($reply->person_id)->last_name }}</h4>
                                @if(Customer::where('person_id', $reply->person_id)->exists())
                                    <p class="text-sm italic">Cliente</p>
                                @elseif(Employee::where('person_id', $reply->person_id)->exists())
                                    <p class="font-bold text-sm italic">Empleado</p>
                                @endif
                            </div>
                            <p class="w-5/6 pl-3 text-[15px]">{{ $reply->content }}</p>
                        </div>
                        <div class="flex flex-row space-x-4 items-end">
                            @if($reply->person_id === auth()->user()->person->id)
                                <x-elements.link-button href="{{ route('forum.reply.edit', ['reply' => $reply->id]) }}">
                                    <x-slot:text>Editar Respuesta</x-slot:text>
                                </x-elements.link-button>
                            @endif
                            <div class="{{ \Illuminate\Support\Facades\Auth::getCurrentGuard() === 'manager' || $reply->person_id === auth()->user()->person->id ? '' : 'hidden' }}">
                                <form action="{{ route('forum.reply.destroy', ['reply' => $reply]) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="relative inline-flex items-center justify-center p-0.5 me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-rose-400 to-red-600 group-hover:from-rose-400 group-hover:to-red-600 hover:text-white">
                                    <span class="relative px-5 py-2.5 transition-all ease-in duration-75 bg-white rounded-md group-hover:bg-transparent w-full">
                                        Eliminar
                                    </span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-layouts.app>
