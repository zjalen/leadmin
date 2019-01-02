@if(\Zjalen\Leadmin\Facades\Leadmin::user()->visible($item['roles']))
    @if(!isset($item['children']))
        <li>
            @if(url()->isValidUrl($item['url']))
                <a href="{{ $item['url'] }}" target="_blank">
            @else
                 <a href="{{ url($item['url']) }}" class="J_menuItem">
            @endif
                <i style="width: 25px;text-align: center" class="fa {{$item['icon']}}"></i>
                <span>{{$item['title']}}</span>
            </a>
        </li>
    @else
        <li class="sub-menu">
            <a href="javascript:;" class="">
                <i style="width: 25px; text-align: center" class="fa {{$item['icon']}}"></i>
                <span>{{$item['title']}}</span>
                <span class="arrow"></span>
            </a>
            <ul class="sub">
                @foreach($item['children'] as $item)
                    @include('leadmin.layouts._menu', $item)
                @endforeach
            </ul>
        </li>
    @endif
@endif