<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<style>
    #budgetTree{
        list-style-type: none;
        color: #666666;
    }

    #budgetTree [data-tree-click] {
        cursor: pointer;
        color: #999999;
        font-weight: bold;
        font-size: 1.2em;
    }

    #budgetTree .closed [data-tree-click] {
        padding-left: 12px;
        background-image: url({{asset('assets/images/icon/plus_minus.gif')}});
        background-repeat: no-repeat;
        background-position: 0px 6px;
        color: #FF00000 !important;
    }

    #budgetTree .open [data-tree-click] {
        padding-left: 12px;
        background-image: url({{asset('assets/images/icon/plus_minus.gif')}});
        background-repeat: no-repeat;
        background-position: 0px -94px;
        color: #FF00000 !important;
    }

    #budgetTree .end {
        padding-left: 12px;
        color: #BBBBBB;
    }

    #budgetTree .data-tree-level1 {
        margin-left: 10px;
    }

    #budgetTree .data-tree-level2 {
        margin-left: 20px;
    }

    #budgetTree .data-tree-level3 {
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

<ul id="budgetTree" class="pl-0">
    @forelse($budget_heads as $option)
        <li data-tree-branch="{{ $option->node_path }}" class="text-dark lirow">
                <span data-tree-click="{{ $option->node_path }}" class="text-primary">
                <small>{{ $option->budget_head_name }} </small>
                </span>
                <button class="btn btn-primary btn-sm head_id float-right"
                        id="{{$option->budget_head_id}}">Select
                </button>
        </li>
    @empty
        <span>Nothing found</span>
    @endforelse
</ul>
<script>
    $('#budgetTree').dataTree({
        defaultOpen: true
    });
</script>
