<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<style>
    #tree {
        list-style-type: none;
        color: #666666;
    }

    #tree [data-tree-click] {
        cursor: pointer;
        color: #999999;
        font-weight: bold;
        font-size: 1.2em;
    }

    #tree .closed [data-tree-click] {
        padding-left: 12px;
        background-image: url({{asset('assets/images/icon/plus_minus.gif')}});
        background-repeat: no-repeat;
        background-position: 0px 6px;
        color: #FF00000 !important;
    }

    #tree .open [data-tree-click] {
        padding-left: 12px;
        background-image: url({{asset('assets/images/icon/plus_minus.gif')}});
        background-repeat: no-repeat;
        background-position: 0px -94px;
        color: #FF00000 !important;
    }

    #tree .end {
        padding-left: 12px;
        color: #BBBBBB;
    }

    #tree .data-tree-level1 {
        margin-left: 10px;
    }

    #tree .data-tree-level2 {
        margin-left: 20px;
    }

    #tree .data-tree-level3 {
        margin-left: 30px;
    }

    #container {
        margin: 150px auto;
    }

    .lirow {
        padding: 5px;
    }

    .lirow:hover {
        background: #8080802b;
    }
</style>

<ul id="tree" class="pl-0">
    @forelse($gl_chart_list as $option)
        <li data-tree-branch="{{ $option->node_path }}" class="text-dark lirow">
                <span data-tree-click="{{ $option->node_path }}" class="text-primary">
                <small>{{ $option->gl_acc_name }} </small>
                </span>
                <button class="btn btn-primary btn-sm gl-coa float-right"
                        id="{{$option->gl_acc_id}}">Select
                </button>
        </li>
    @empty
        <span>Nothing found</span>
    @endforelse
</ul>
<script>
    $('#tree').dataTree({
        defaultOpen: true
    });
</script>
