@php
    use App\Models\Customer;
    use App\Models\Employee;
    use App\Models\Person;

    $avatarColors = [
        'bg-red-400', 'bg-blue-400', 'bg-green-400', 'bg-yellow-400',
        'bg-purple-400', 'bg-pink-400', 'bg-indigo-400', 'bg-teal-400',
        'bg-orange-400', 'bg-cyan-400'
    ];
@endphp
<x-layouts.app>
    <x-slot:title>
        {{ $discussion->title }}
    </x-slot:title>
    <div class="bg-gray-50 h-screen">
        <div class="bg-white grid grid-cols-2 gap-x-4 p-4 max-h-screen">
            <div class="px-3 bg-gray-100 rounded-lg shadow-md max-h-screen h-screen">
                <div class="flex flex-row justify-between items-center">
                    <div class="flex flex-row space-x-4 items-center my-2">
                        <h3>{{ Customer::find($discussion->customer_id)->person->full_name }}</h3>
                        <h3 class="text-sm text-gray-500">{{ $discussion->created_at->diffForHumans() }}</h3>
                    </div>
                    <div class="p-2 border-2 border-yellow-400 rounded-full text-yellow-400 bg-white my-2 text-sm">
                        {{ $discussion->section->name }}
                    </div>
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
                                @if(Auth::getCurrentGuard() === 'customer' && $discussion->customer_id === auth()->user()->id)
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
                    @php
                        $randomColor = $avatarColors[array_rand($avatarColors)];
                    @endphp
                    <div class="my-2 flex flex-row justify-between">
                        <div class="flex flex-row gap-x-3 w-full">
                            <div class="p-2 w-10 h-10 text-white font-bold flex items-center justify-center {{ $randomColor }} rounded-full text-2xl">{{ Person::find($reply->person_id)->first_name[0] }}</div>
                            <div class="flex flex-col w-full leading-1.5 p-4 border-white bg-white rounded-e-xl rounded-es-xl shadow">
                                <div class="flex items-center space-x-2 justify-between">
                                    <div class="space-x-1">
                                        <span class="text-md font-semibold text-gray-900 dark:text-white">{{ Person::find($reply->person_id)->full_name }}</span>
                                        <span class="text-sm  {{ Customer::where('person_id', $reply->person_id)->exists() ? 'font-normal text-gray-500' : 'font-bold text-blue-400' }}">{{ Customer::where('person_id', $reply->person_id)->exists() ? 'Cliente' : 'Empleado' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-sm font-normal text-gray-500 dark:text-gray-400">{{ $reply->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <p class="text-sm font-normal py-2.5 text-gray-900 dark:text-white">{{ $reply->content }}</p>
                                <div class="flex flex-row items-center justify-end gap-x-4">
                                    <div>
                                        @if($reply->person_id === auth()->user()->person->id)
                                            <a href="{{ route('forum.reply.edit', ['reply' => $reply->id]) }}" class="">
                                                <i class="fa-solid fa-pen-to-square text-yellow-400"></i>
                                            </a>
                                        @endif
                                    </div>
                                    <div class="{{ \Illuminate\Support\Facades\Auth::getCurrentGuard() === 'manager' || $reply->person_id === auth()->user()->person->id ? '' : 'hidden' }}">
                                        <form action="{{ route('forum.reply.destroy', ['reply' => $reply]) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="cursor-pointer">
                                                <i class="fa-solid fa-trash text-red-600"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-layouts.app>
