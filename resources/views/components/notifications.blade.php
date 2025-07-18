  @if(auth()->user()->unreadNotifications->isNotEmpty())
     
        
          @foreach(auth()->user()->unreadNotifications as $notification)
              <div class="dropdown-divider"></div>
              <a href="{{ $notification->data['data']['link'] }}" class="dropdown-item">
                  <!-- Message Start -->
                  <div class="media">
                      <img src="{{ $notification->data['data']['avatar'] }}" alt="" class="img-size-50 mr-3 img-circle">
                      <div class="media-body">
                          <h3 class="dropdown-item-title">
                              {{ $notification->data['data']['name'] }}
                          </h3>
                          <p class="text-sm">{{ $notification->data['data']['message'] }}</p>
                          <p class="text-sm text-muted">
                              <i class="far fa-clock mr-1"></i> {{ $notification->created_at->diffForHumans() }}
                          </p>
                      </div>
                  </div>
                  <!-- Message End -->
              </a>
              <div class="dropdown-divider"></div>
          @endforeach
     
  @endif