<section class="mt-3">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <th class="text-uppercase w-75">producto</th>
                            <th class="text-uppercase w-25 text-center">destacado</th>
                        </thead>
                        <form action="" method="post">
                            @csrf
                            <tbody>
                                @foreach( $data[ 'productos' ] AS $p )
                                <tr>
                                    @php
                                    $name = $p->name;
                                    if( !empty( $p->categoria ) ) {
                                        $name = "{$p->categoria->name}, <strong>${name}</strong>";
                                        if( !empty( $p->categoria->familia ) )
                                            $name = "{$p->categoria->familia->name}, {$name}";
                                    }
                                    @endphp
                                    <td>{!! $name !!}</td>
                                    <td class="w-25 text-center">
                                        <input type="checkbox" name="destacado[]" value="{{ $p['id'] }}" @if($p['is_destacado']) checked @endif/>
                                        <input type="hidden" name="ids[]" value="{{ $p['id'] }}"/>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2">
                                        <div class="d-flex justify-content-center">
                                            {{ $data[ 'productos' ]->links() }}
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                    <button type="submit" class="btn btn-success btn-block rounded-0 text-center text-uppercase">guardar</button>
                                    </td>
                                </tr>
                            </tfoot>
                        </form>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>