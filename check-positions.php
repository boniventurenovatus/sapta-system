App\Models\Position::all(['id','title','code','organizational_unit_id'])->each(function($p){
    echo $p->id . ' | ' . $p->title . ' | ' . $p->code . ' | ' . $p->organizational_unit_id . PHP_EOL;
});
