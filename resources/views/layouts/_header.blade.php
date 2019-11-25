<nav class="navbar navbar-expand-lg navbar-light bg-light navbar-static-top">
    <div class="container">
        <a href="{{ url('/') }}" class="navbar-brand">LaraBBS</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item {{ (Route::currentRouteName() == 'topics.index') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('topics.index') }}">话题</a>
                </li>
                <li class="nav-item {{ (Route::currentRouteName() == 'categories.show' && isset($category->id) && $category->id == 1) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('categories.show', 1) }}">分享</a>
                </li>
                <li class="nav-item {{ (Route::currentRouteName() == 'categories.show' && isset($category->id) && $category->id == 2) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('categories.show', 2) }}">教程</a>
                </li>
                <li class="nav-item {{ (Route::currentRouteName() == 'categories.show' && isset($category->id) && $category->id == 3) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('categories.show', 3) }}">问答</a>
                </li>
                <li class="nav-item {{ (Route::currentRouteName() == 'categories.show' && isset($category->id) && $category->id == 4) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('categories.show', 4) }}">公告</a>
                </li>
            </ul>

            <ul class="navbar-nav navbar-right">
                @guest
                    <li class="nav-item"><a href="{{ route('login') }}" class="nav-link">登录</a></li>
                    <li class="nav-item"><a href="{{ route('register') }}" class="nav-link">注册</a></li>
                @else
                    <li class="nav-item">
                        <a class="nav-link mt-1 mr-3 font-weight-bold" href="{{ route('topics.create') }}">
                            <i class="fa fa-plus"></i>
                        </a>
                    </li>
                    <li class="nav-item notification-badge">
                        <a class="nav-link mr-3 badge badge-pill badge-{{ Auth::user()->notification_count > 0 ? 'hint' : 'secondary' }} text-white" href="{{ route('notifications.index') }}">
                            {{ Auth::user()->notification_count }}
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" id="navbarDropdown" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="{{ Auth::user()->avatar }}"
                                  class="img-responsive img-circle" width="30px" height="30px">
                            {{ Auth::user()->name }}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdoen">
                            @can('manage_contents')
                                <a class="dropdown-item" href="{{ url(config('administrator.uri')) }}">
                                    <i class="fas fa-tachometer-alt mr-2">
                                        管理后台
                                    </i>
                                </a>
                                <div class="dropdown-divider"></div>
                            @endcan
                            <a href="{{ route('users.show', Auth::id()) }}" class="dropdown-item">
                                <i class="far fa-user mr-2"></i>
                                个人中心
                            </a>
                            <a href="{{ route('users.edit', Auth::id()) }}" class="dropdown-item">
                                <i class="far fa-edit mr-2"></i>
                                编辑资料
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item" id="logout">
                                <form action="{{ route('logout') }}" method="POST">
                                    {{ csrf_field() }}
                                    <button class="btn btn-block btn-danger" type="submit" name="button">退出</button>
                                </form>
                            </a>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>