@if($users->isNotEmpty())
    @foreach($users as $user)
        <li class="@if($user->id == request()->segment(2)) active @endif">
            <a href="{{ route('conversations.index', $user->id) }}">
                @if(\Storage::disk('public')->has($user->avatar))
                    <img class="contacts-list-img" src="{{ asset('storage/'.$user->avatar) }}" alt="" >
                @else
                    <img class="contacts-list-img" src="{{ asset('images/no-img-100x92.jpg') }}" alt="" >
                @endif
                <div class="contacts-list-info">
                    <span class="contacts-list-name">
                        {{ $user->full_name }}
                        <small class="contacts-list-date pull-right">{{ $user->created_at->format('d/m/Y') }}</small>
                    </span>
                    <span class="contacts-list-msg">{{ $user->roles->first()->name }}</span>
                </div>
            </a>
        </li>
    @endforeach
@else
    <li>No contacts found!</li>
@endif