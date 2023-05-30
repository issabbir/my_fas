<!-- BEGIN: Main Menu-->
<div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
	<div class="navbar-header">
		<ul class="nav navbar-nav flex-row">
			<li class="nav-item mr-auto">
				<a class="navbar-brand mt-0" href="{{route('dashboard')}}">
					<img src="{{asset('assets/images/logo/cpa-logo.png')}}" alt="users view avatar" class="img-fluid"/>
				</a>
			</li>
			<li class="nav-item nav-toggle">
				<a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse">
					<i class="bx bx-x d-block d-xl-none font-medium-4 primary"></i>
					<i class="toggle-icon bx bx-disc font-medium-4 d-none d-xl-block primary" data-ticon="bx-disc"></i>
				</a>
			</li>
		</ul>
	</div>
	<div class="shadow-bottom"></div>
	<div class="main-menu-content mt-1">
		<ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation" data-icon-style="lines">
            <li class="nav-item sidebar-group-active">
                <a href="{{env('DASHBOARD_URL')}}"><i class="bx bx-home" data-icon="users"></i><span class="menu-item" data-i18n="Invoice List">Dashboard</span></a>
            </li>
            {{--Configuration Setup Module start--}}
            @php
                $glActiveMenus = \App\Helpers\GlClass::activeMenus(\Illuminate\Support\Facades\Route::currentRouteName()) ;
                $hasActiveChildGlMenu = \App\Helpers\GlClass::hasChildMenu(\Illuminate\Support\Facades\Route::currentRouteName());
            @endphp
            @foreach(\App\Helpers\ConfigurationClass::menuSetup() as $menu)
                @if ($menu->module->enabled == 'Y')
                    <li class="nav-item {{  trim(Route::currentRouteName()) === trim($menu->menu_name) ? 'sidebar-group-active open ok' : 'none' }}"><a href=""><i class="bx bx-notepad" data-icon="users"></i><span class="menu-item" data-i18n="InvoiceLineType List">{{$menu->menu_name}}</span></a>
                        <ul class="menu-content">
                            @foreach($menu->sub_menus as $submenu)
                                @if (Auth::user()->hasGrantAll() || in_array($submenu->submenu_id,$menu->role_submenus))
                                    <li class="{{($hasActiveChildGlMenu )?'open has-sub':((in_array($submenu->submenu_id,$glActiveMenus) && $submenu->route_name)?'active':'') }}">
                                        <a href="{{ (isset($submenu->action_name) && ($submenu->menu_id == \App\Enums\ModuleInfo::CONFIGURATION_MODULE_ID))  ? route($submenu->action_name) : $submenu->route_name}}" @if ($submenu->route_name) class="link_item" @endif><i class="bx bx-right-arrow-alt"></i><span class="menu-item" data-i18n="Third Level">{{$submenu->submenu_name}}</span></a>
                                        @if (count($submenu->submenus)>0)
                                            <ul class="menu-content">
                                                @foreach($submenu->submenus as $smenu)
                                                    @if (Auth::user()->hasGrantAll() || in_array($smenu->submenu_id,$menu->role_submenus))
                                                        <li class="{{in_array($smenu->submenu_id,$glActiveMenus)?'active':''}}">
                                                            @if (strpos($smenu->route_name, '.xdo') !== false)
                                                                <a href="{{ (isset($smenu->action_name) && ($smenu->menu_id == \App\Enums\ModuleInfo::CONFIGURATION_MODULE_ID))  ? request()->root().route($smenu->action_name) : request()->root().$smenu->route_name}}" class="link_item" target="_blank"><i class="bx bx-right-arrow-alt"></i><span class="menu-item">{{$smenu->submenu_name}}</span></a>
                                                            @else
                                                                <a href="{{ (isset($smenu->action_name) && ($smenu->menu_id == \App\Enums\ModuleInfo::CONFIGURATION_MODULE_ID))  ? route($smenu->action_name) : $smenu->route_name}}" class="link_item"@if (strpos($smenu->route_name, '.xdo') !== false) target="_blank" @endif><i class="bx bx-right-arrow-alt"></i><span class="menu-item">{{$smenu->submenu_name}}</span></a>
                                                            @endif
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </li>
                @endif
            @endforeach
            {{--Configuration Setup Module end--}}
            @php
                $glActiveMenus = \App\Helpers\GlClass::activeMenus(\Illuminate\Support\Facades\Route::currentRouteName()) ;
                $hasActiveChildGlMenu = \App\Helpers\GlClass::hasChildMenu(\Illuminate\Support\Facades\Route::currentRouteName());
            @endphp
            @foreach(\App\Helpers\GlClass::menuSetup() as $menu)
                @if ($menu->module->enabled == 'Y')
                    <li class="nav-item {{  trim(Route::currentRouteName()) === trim($menu->menu_name) ? 'sidebar-group-active open ok' : 'none' }}"><a href=""><i class="bx bx-notepad" data-icon="users"></i><span class="menu-item" data-i18n="InvoiceLineType List">{{$menu->menu_name}}</span></a>
                        <ul class="menu-content">
                            @foreach($menu->sub_menus as $submenu)
                                @if (Auth::user()->hasGrantAll() || in_array($submenu->submenu_id,$menu->role_submenus))
                                    <li class="{{($hasActiveChildGlMenu )?'open has-sub':((in_array($submenu->submenu_id,$glActiveMenus) && $submenu->route_name)?'active':'') }}">
                                        <a href="{{ (isset($submenu->action_name) && ($submenu->menu_id == \App\Enums\ModuleInfo::GL_MODULE_ID))  ? route($submenu->action_name) : $submenu->route_name}}" @if ($submenu->route_name) class="link_item" @endif><i class="bx bx-right-arrow-alt"></i><span class="menu-item" data-i18n="Third Level">{{$submenu->submenu_name}}</span></a>
                                        @if (count($submenu->submenus)>0)
                                            <ul class="menu-content">
                                                @foreach($submenu->submenus as $smenu)
                                                    @if (Auth::user()->hasGrantAll() || in_array($smenu->submenu_id,$menu->role_submenus))
                                                        <li class="{{in_array($smenu->submenu_id,$glActiveMenus)?'active':''}}">
                                                            @if (strpos($smenu->route_name, '.xdo') !== false)
                                                                <a href="{{ (isset($smenu->action_name) && ($smenu->menu_id == \App\Enums\ModuleInfo::GL_MODULE_ID))  ? request()->root().route($smenu->action_name) : request()->root().$smenu->route_name}}" class="link_item" target="_blank"><i class="bx bx-right-arrow-alt"></i><span class="menu-item">{{$smenu->submenu_name}}</span></a>
                                                            @else
                                                                <a href="{{ (isset($smenu->action_name) && ($smenu->menu_id == \App\Enums\ModuleInfo::GL_MODULE_ID))  ? route($smenu->action_name) : $smenu->route_name}}" class="link_item"@if (strpos($smenu->route_name, '.xdo') !== false) target="_blank" @endif><i class="bx bx-right-arrow-alt"></i><span class="menu-item">{{$smenu->submenu_name}}</span></a>
                                                            @endif
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </li>
                @endif
            @endforeach

            @php
                $arActiveMenus = \App\Helpers\ArClass::activeMenus(\Illuminate\Support\Facades\Route::currentRouteName()) ;
                $hasActiveChildArMenu = \App\Helpers\ArClass::hasChildMenu(\Illuminate\Support\Facades\Route::currentRouteName());
            @endphp
            @foreach(\App\Helpers\ArClass::menuSetup() as $menu)
                @if ($menu->module->enabled == 'Y')
                    <li class="nav-item {{  trim(Route::currentRouteName()) === trim($menu->menu_name) ? 'sidebar-group-active open ok' : 'none' }}"><a href=""><i class="bx bx-notepad" data-icon="users"></i><span class="menu-item" data-i18n="Invoice List">{{$menu->menu_name}}</span></a>
                        <ul class="menu-content">
                            @foreach($menu->sub_menus as $submenu)
                                @if (Auth::user()->hasGrantAll() || in_array($submenu->submenu_id,$menu->role_submenus))
                                    <li class="{{($hasActiveChildArMenu )?'open has-sub':((in_array($submenu->submenu_id,$arActiveMenus) && $submenu->route_name)?'active':'') }}">
                                        <a href="{{ (isset($submenu->action_name) && ($submenu->menu_id == \App\Enums\ModuleInfo::AR_MODULE_ID))  ? route($submenu->action_name) : $submenu->route_name}}" @if ($submenu->route_name) class="link_item" @endif><i class="bx bx-right-arrow-alt"></i><span class="menu-item" data-i18n="Third Level">{{$submenu->submenu_name}}</span></a>
                                        @if (count($submenu->submenus)>0)
                                            <ul class="menu-content">
                                                @foreach($submenu->submenus as $smenu)
                                                    @if (Auth::user()->hasGrantAll() || in_array($smenu->submenu_id,$menu->role_submenus))
                                                        <li class="{{in_array($smenu->submenu_id,$arActiveMenus)?'active':''}}">
                                                            @if (strpos($smenu->route_name, '.xdo') !== false)
                                                                <a href="{{ (isset($smenu->action_name) && ($smenu->menu_id == \App\Enums\ModuleInfo::AR_MODULE_ID))  ? request()->root().route($smenu->action_name) : request()->root().$smenu->route_name}}" class="link_item" target="_blank"><i class="bx bx-right-arrow-alt"></i><span class="menu-item">{{$smenu->submenu_name}}</span></a>
                                                            @else
                                                                <a href="{{ (isset($smenu->action_name) && ($smenu->menu_id == \App\Enums\ModuleInfo::AR_MODULE_ID))  ? route($smenu->action_name) : $smenu->route_name}}" class="link_item"@if (strpos($smenu->route_name, '.xdo') !== false) target="_blank" @endif><i class="bx bx-right-arrow-alt"></i><span class="menu-item">{{$smenu->submenu_name}}</span></a>
                                                            @endif
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </li>
                @endif
            @endforeach

            @php
				$apActiveMenus = \App\Helpers\ApClass::activeMenus(\Illuminate\Support\Facades\Route::currentRouteName()) ;
				$hasActiveChildApMenu = \App\Helpers\ApClass::hasChildMenu(\Illuminate\Support\Facades\Route::currentRouteName());
			@endphp
			@foreach(\App\Helpers\ApClass::menuSetup() as $menu)
				@if ($menu->module->enabled == 'Y')
					<li class="nav-item {{  trim(Route::currentRouteName()) === trim($menu->menu_name) ? 'sidebar-group-active open ok' : 'none' }}"><a href=""><i class="bx bx-notepad" data-icon="users"></i><span class="menu-item" data-i18n="Invoice List">{{$menu->menu_name}}</span></a>
						<ul class="menu-content">
							@foreach($menu->sub_menus as $submenu)
								@if (Auth::user()->hasGrantAll() || in_array($submenu->submenu_id,$menu->role_submenus))
									<li class="{{($hasActiveChildApMenu )?'open has-sub':((in_array($submenu->submenu_id,$apActiveMenus) && $submenu->route_name)?'active':'') }}">
										<a href="{{ (isset($submenu->action_name) && ($submenu->menu_id == \App\Enums\ModuleInfo::AP_MODULE_ID))  ? route($submenu->action_name) : $submenu->route_name}}" @if ($submenu->route_name) class="link_item" @endif><i class="bx bx-right-arrow-alt"></i><span class="menu-item" data-i18n="Third Level">{{$submenu->submenu_name}}</span></a>
										@if (count($submenu->submenus)>0)
											<ul class="menu-content">
												@foreach($submenu->submenus as $smenu)
													@if (Auth::user()->hasGrantAll() || in_array($smenu->submenu_id,$menu->role_submenus))
														<li class="{{in_array($smenu->submenu_id,$apActiveMenus)?'active':''}}">
															@if (strpos($smenu->route_name, '.xdo') !== false)
																<a href="{{ (isset($smenu->action_name) && ($smenu->menu_id == \App\Enums\ModuleInfo::AP_MODULE_ID))  ? request()->root().route($smenu->action_name) : request()->root().$smenu->route_name}}" class="link_item" target="_blank"><i class="bx bx-right-arrow-alt"></i><span class="menu-item">{{$smenu->submenu_name}}</span></a>
															@else
																<a href="{{ (isset($smenu->action_name) && ($smenu->menu_id == \App\Enums\ModuleInfo::AP_MODULE_ID))  ? route($smenu->action_name) : $smenu->route_name}}" class="link_item"@if (strpos($smenu->route_name, '.xdo') !== false) target="_blank" @endif><i class="bx bx-right-arrow-alt"></i><span class="menu-item">{{$smenu->submenu_name}}</span></a>
															@endif
														</li>
													@endif
												@endforeach
											</ul>
										@endif
									</li>
								@endif
							@endforeach
						</ul>
					</li>
				@endif
			@endforeach

			@php
				$cmActiveMenus = \App\Helpers\CmClass::activeMenus(\Illuminate\Support\Facades\Route::currentRouteName()) ;
				$hasActiveChildCmMenu = \App\Helpers\CmClass::hasChildMenu(\Illuminate\Support\Facades\Route::currentRouteName());
			@endphp
			@foreach(\App\Helpers\CmClass::menuSetup() as $menu)
				@if ($menu->module->enabled == 'Y')
					<li class="nav-item {{  trim(Route::currentRouteName()) === trim($menu->menu_name) ? 'sidebar-group-active open ok' : 'none' }}"><a href=""><i class="bx bx-notepad" data-icon="users"></i><span class="menu-item" data-i18n="InvoiceLineType List">{{$menu->menu_name}}</span></a>
						<ul class="menu-content">
							@foreach($menu->sub_menus as $submenu)
								@if (Auth::user()->hasGrantAll() || in_array($submenu->submenu_id,$menu->role_submenus))
									<li class="{{($hasActiveChildCmMenu )?'open has-sub':((in_array($submenu->submenu_id,$cmActiveMenus) && $submenu->route_name)?'active':'') }}">
										<a href="{{ (isset($submenu->action_name) && ($submenu->menu_id == \App\Enums\ModuleInfo::CM_MODULE_ID))  ? route($submenu->action_name) : $submenu->route_name}}" @if ($submenu->route_name) class="link_item" @endif><i class="bx bx-right-arrow-alt"></i><span class="menu-item" data-i18n="Third Level">{{$submenu->submenu_name}}</span></a>
										@if (count($submenu->submenus)>0)
											<ul class="menu-content">
												@foreach($submenu->submenus as $smenu)
													@if (Auth::user()->hasGrantAll() || in_array($smenu->submenu_id,$menu->role_submenus))
														<li class="{{in_array($smenu->submenu_id,$cmActiveMenus)?'active':''}}">
															@if (strpos($smenu->route_name, '.xdo') !== false)
																<a href="{{ (isset($smenu->action_name) && ($smenu->menu_id == \App\Enums\ModuleInfo::CM_MODULE_ID))  ? request()->root().route($smenu->action_name) : request()->root().$smenu->route_name}}" class="link_item" target="_blank"><i class="bx bx-right-arrow-alt"></i><span class="menu-item">{{$smenu->submenu_name}}</span></a>
															@else
																<a href="{{ (isset($smenu->action_name) && ($smenu->menu_id == \App\Enums\ModuleInfo::CM_MODULE_ID))  ? route($smenu->action_name) : $smenu->route_name}}" class="link_item"@if (strpos($smenu->route_name, '.xdo') !== false) target="_blank" @endif><i class="bx bx-right-arrow-alt"></i><span class="menu-item">{{$smenu->submenu_name}}</span></a>
															@endif
														</li>
													@endif
												@endforeach
											</ul>
										@endif
									</li>
								@endif
							@endforeach
						</ul>
					</li>
				@endif
			@endforeach

            @php
                $budgetMgtActiveMenus = \App\Helpers\BudgetMgtClass::activeMenus(\Illuminate\Support\Facades\Route::currentRouteName()) ;
                $hasActiveChildBudgetMgtMenu = \App\Helpers\BudgetMgtClass::hasChildMenu(\Illuminate\Support\Facades\Route::currentRouteName());
            @endphp
            @foreach(\App\Helpers\BudgetMgtClass::menuSetup() as $menu)
                @if ($menu->module->enabled == 'Y')
                    <li class="nav-item {{  trim(Route::currentRouteName()) === trim($menu->menu_name) ? 'sidebar-group-active open ok' : 'none' }}"><a href=""><i class="bx bx-notepad" data-icon="users"></i><span class="menu-item" data-i18n="InvoiceLineType List">{{$menu->menu_name}}</span></a>
                        <ul class="menu-content">
                            @foreach($menu->sub_menus as $submenu)
                                @if (Auth::user()->hasGrantAll() || in_array($submenu->submenu_id,$menu->role_submenus))
                                    <li class="{{($hasActiveChildBudgetMgtMenu )?'open has-sub':((in_array($submenu->submenu_id,$budgetMgtActiveMenus) && $submenu->route_name)?'active':'') }}">
                                        <a href="{{ (isset($submenu->action_name) && ($submenu->menu_id == \App\Enums\ModuleInfo::BUDGET_MGT_MODULE_ID))  ? route($submenu->action_name) : $submenu->route_name}}" @if ($submenu->route_name) class="link_item" @endif><i class="bx bx-right-arrow-alt"></i><span class="menu-item" data-i18n="Third Level">{{$submenu->submenu_name}}</span></a>
                                        @if (count($submenu->submenus)>0)
                                            <ul class="menu-content">
                                                @foreach($submenu->submenus as $smenu)
                                                    @if (Auth::user()->hasGrantAll() || in_array($smenu->submenu_id,$menu->role_submenus))
                                                        <li class="{{in_array($smenu->submenu_id,$budgetMgtActiveMenus)?'active':''}}">
                                                            @if (strpos($smenu->route_name, '.xdo') !== false)
                                                                <a href="{{ (isset($smenu->action_name) && ($smenu->menu_id == \App\Enums\ModuleInfo::BUDGET_MGT_MODULE_ID))  ? request()->root().route($smenu->action_name) : request()->root().$smenu->route_name}}" class="link_item" target="_blank"><i class="bx bx-right-arrow-alt"></i><span class="menu-item">{{$smenu->submenu_name}}</span></a>
                                                            @else
                                                                <a href="{{ (isset($smenu->action_name) && ($smenu->menu_id == \App\Enums\ModuleInfo::BUDGET_MGT_MODULE_ID))  ? route($smenu->action_name) : $smenu->route_name}}" class="link_item"@if (strpos($smenu->route_name, '.xdo') !== false) target="_blank" @endif><i class="bx bx-right-arrow-alt"></i><span class="menu-item">{{$smenu->submenu_name}}</span></a>
                                                            @endif
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </li>
                @endif
            @endforeach

            @php
                $budgetMonActiveMenus = \App\Helpers\BudgetMonClass::activeMenus(\Illuminate\Support\Facades\Route::currentRouteName()) ;
                $hasActiveChildBudgetMonMenu = \App\Helpers\BudgetMonClass::hasChildMenu(\Illuminate\Support\Facades\Route::currentRouteName());
            @endphp
            @foreach(\App\Helpers\BudgetMonClass::menuSetup() as $menu)
                @if ($menu->module->enabled == 'Y')
                    <li class="nav-item {{  trim(Route::currentRouteName()) === trim($menu->menu_name) ? 'sidebar-group-active open ok' : 'none' }}"><a href=""><i class="bx bx-notepad" data-icon="users"></i><span class="menu-item" data-i18n="InvoiceLineType List">{{$menu->menu_name}}</span></a>
                        <ul class="menu-content">
                            @foreach($menu->sub_menus as $submenu)
                                @if (Auth::user()->hasGrantAll() || in_array($submenu->submenu_id,$menu->role_submenus))
                                    <li class="{{($hasActiveChildBudgetMonMenu )?'open has-sub':((in_array($submenu->submenu_id,$budgetMonActiveMenus) && $submenu->route_name)?'active':'') }}">
                                        <a href="{{ (isset($submenu->action_name) && ($submenu->menu_id == \App\Enums\ModuleInfo::BUDGET_MON_MODULE_ID))  ? route($submenu->action_name) : $submenu->route_name}}" @if ($submenu->route_name) class="link_item" @endif><i class="bx bx-right-arrow-alt"></i><span class="menu-item" data-i18n="Third Level">{{$submenu->submenu_name}}</span></a>
                                        @if (count($submenu->submenus)>0)
                                            <ul class="menu-content">
                                                @foreach($submenu->submenus as $smenu)
                                                    @if (Auth::user()->hasGrantAll() || in_array($smenu->submenu_id,$menu->role_submenus))
                                                        <li class="{{in_array($smenu->submenu_id,$budgetMonActiveMenus)?'active':''}}">
                                                            @if (strpos($smenu->route_name, '.xdo') !== false)
                                                                <a href="{{ (isset($smenu->action_name) && ($smenu->menu_id == \App\Enums\ModuleInfo::BUDGET_MON_MODULE_ID))  ? request()->root().route($smenu->action_name) : request()->root().$smenu->route_name}}" class="link_item" target="_blank"><i class="bx bx-right-arrow-alt"></i><span class="menu-item">{{$smenu->submenu_name}}</span></a>
                                                            @else
                                                                <a href="{{ (isset($smenu->action_name) && ($smenu->menu_id == \App\Enums\ModuleInfo::BUDGET_MON_MODULE_ID))  ? route($smenu->action_name) : $smenu->route_name}}" class="link_item"@if (strpos($smenu->route_name, '.xdo') !== false) target="_blank" @endif><i class="bx bx-right-arrow-alt"></i><span class="menu-item">{{$smenu->submenu_name}}</span></a>
                                                            @endif
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </li>
                @endif
            @endforeach
		</ul>
	</div>
</div>
<!-- END: Main Menu-->
<!-- END: Header-->
