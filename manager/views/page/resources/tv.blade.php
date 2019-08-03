<div class="tab-page {{ $tabPageName }}" id="{{ $tabIndexPageName }}">
    <h2 class="tab">
        <a href="?a=76&tab={{ $index }}"><i class="{{ $_style['icons_tv'] }}"></i>{{ ManagerTheme::getLexicon('tmplvars') }}</a>
    </h2>

    <script>tpResources.addTabPage(document.getElementById('{{ $tabIndexPageName }}'));</script>

    <div id="{{ $tabIndexPageName }}-info" class="msg-container" style="display:none">
        <div class="element-edit-message-tab">{!! ManagerTheme::getLexicon('tmplvars_management_msg') !!}</div>
        <p class="viewoptions-message">{!! ManagerTheme::getLexicon('view_options_msg') !!}</p>
    </div>

    <div id="_actions">
        <form class="btn-group form-group form-inline">
            <div class="input-group input-group-sm">
                <input type="text" class="form-control filterElements-form" id="{{ $tabIndexPageName }}_search" size="30" placeholder="{{ ManagerTheme::getLexicon('element_filter_msg') }}" />
                <div class="input-group-btn">
                    <a class="btn btn-success" href="{{ (new EvolutionCMS\Models\SiteTmplvar)->makeUrl('actions.new') }}">
                        <i class="{{ $_style['actions_add'] }}"></i>
                        <span>{{ ManagerTheme::getLexicon('new_tmplvars') }}</span>
                    </a>
                    <a class="btn btn-secondary" href="{{ (new EvolutionCMS\Models\SiteTmplvar)->makeUrl('actions.sort') }}">
                        <i class="{{ $_style['actions_sort'] }}"></i>
                        <span>{{ ManagerTheme::getLexicon('template_tv_edit') }}</span>
                    </a>
                    <a class="btn btn-secondary" href="javascript:;" id="{{ $tabIndexPageName }}-help">
                        <i class="{{ $_style['icons_tooltip'] }}"></i>
                        <span>{{ ManagerTheme::getLexicon('help') }}</span>
                    </a>
                    <a class="btn btn-secondary switchform-btn" href="javascript:;" data-target="switchForm_{{ $tabIndexPageName }}">
                        <i class="{{ $_style['icons_bars'] }}"></i>
                        <span>{{ ManagerTheme::getLexicon('btn_view_options') }}</span>
                    </a>
                </div>
            </div>
        </form>
    </div>

    @include('manager::page.resources.helper.switchButtons', ['id' => $tabIndexPageName])

    <div class="clearfix"></div>
    <div class="panel-group no-transition">
        <div id="{{ $tabIndexPageName }}_content" class="resourceTable panel panel-default">
            @if(isset($outCategory) && $outCategory->count() > 0)
                @component('manager::partials.panelCollapse', ['name' => $tabIndexPageName . '_content', 'id' => 0, 'title' => ManagerTheme::getLexicon('no_category')])
                    <ul class="elements">
                        @foreach($outCategory as $item)
                            @include('manager::page.resources.elements.tv', compact('item', 'tabIndexPageName'))
                        @endforeach
                    </ul>
                @endcomponent
            @endif

            @if(isset($categories))
                @foreach($categories as $cat)
                    @component('manager::partials.panelCollapse', ['name' => $tabIndexPageName . '_content', 'id' => $cat->id, 'title' => $cat->name])
                        <ul class="elements">
                            @foreach($cat->tvs as $item)
                                @include('manager::page.resources.elements.tv', compact('item', 'tabIndexPageName'))
                            @endforeach
                        </ul>
                    @endcomponent
                @endforeach
            @endif
        </div>
    </div>
    <div class="clearfix"></div>
</div>

@push('scripts.bot')
    <script>
        initQuicksearch('{{ $tabIndexPageName }}_search', '{{ $tabIndexPageName }}_content');
        initViews('tv', '{{ $tabIndexPageName }}', '{{ $tabIndexPageName }}_content');
    </script>
@endpush
