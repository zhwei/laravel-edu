<?php

namespace App\Admin\Actions;

use App\Services\LineService;
use Encore\Admin\Actions\BatchAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class LineMulticastTextMessage extends BatchAction
{
    public $name = 'Line Multicast';

    public function handle(Collection $collection, Request $request)
    {
        $lineIds = $collection->pluck('line_id')->unique()->all();
        if (!$lineIds) {
            return $this->response()->error('发送失败，所有学生均未绑定 line');
        }

        $line = new LineService();
        $line->multicastTextMessage($lineIds, $request->get('content'));
        $count = count($lineIds);

        return $this->response()->success("共发送 {$count} 个学生")->refresh();
    }

    public function form()
    {
        $this->textarea('content', '消息内容')->rules('required');
    }
}
